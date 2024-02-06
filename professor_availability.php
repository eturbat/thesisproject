<?php

session_start();

if (!isset($_SESSION['professor_logged_in'])) {
    header('Location: professor_login.php');
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

// Fetch the defense schedule dates
$query = "SELECT start_date, end_date FROM defense_schedule ORDER BY id DESC LIMIT 1";
$result = $mysqli->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $start_date = new DateTime($row['start_date']);
    $end_date = new DateTime($row['end_date']);
    $end_date->modify('+1 day');
} else {
    echo "No defense schedule dates are set.";
    exit;
}

// Fetch professors
$profQuery = "SELECT * FROM professor_list";
$profResult = $mysqli->query($profQuery);

$professors = [];
if ($profResult) {
    while ($row = $profResult->fetch_assoc()) {
        $professors[] = $row;
    }
}

// Fetch unique available timeslots across all rooms within the defense schedule dates
$uniqueTimeslotsQuery = "SELECT DISTINCT date, timeslot FROM rooms WHERE date BETWEEN ? AND ?";
$uniqueTimeslotsStmt = $mysqli->prepare($uniqueTimeslotsQuery);
$startDateStr = $start_date->format('Y-m-d');
$endDateStr = $end_date->format('Y-m-d');
$uniqueTimeslotsStmt->bind_param("ss", $startDateStr, $endDateStr);
$uniqueTimeslotsStmt->execute();
$uniqueTimeslotsResult = $uniqueTimeslotsStmt->get_result();

$uniqueTimeslots = [];
while ($row = $uniqueTimeslotsResult->fetch_assoc()) {
    $uniqueTimeslots[$row['date']][] = $row['timeslot'];
}
$uniqueTimeslotsStmt->close();

// Sort timeslots for each date
foreach ($uniqueTimeslots as &$timeslots) {
    usort($timeslots, function($a, $b) {
        return strtotime(explode('-', $a)[0]) - strtotime(explode('-', $b)[0]);
    });
}
unset($timeslots); // Break the reference with the last element

// Group dates by weeks
$period = new DatePeriod($start_date, new DateInterval('P1D'), $end_date);
$weekDays = [];
foreach ($period as $date) {
    if ($date->format('N') < 6) { // Exclude weekends
        $weekDays[$date->format("W")][] = $date->format("Y-m-d");
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <title>Professor Availability</title>
    <style>
        .availability-container {
            margin-top: 20px;
        }
        .week-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .date-container {
            flex-grow: 1;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-right: 10px;
        }
        .timeslot {
            margin: 5px;
        }
        .timeslot-label {
            margin-left: 10px;
        }

        .container button {
            margin-top: 0px;
            float: right;
        }

        .checkmark-circle {
        width: 80px;
        height: 80px;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        margin: 20px;
        }

        .background {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #4bb543;
        position: absolute;
        }

        .checkmark {
        transform: rotate(45deg);
        height: 30px;
        width: 15px;
        display: block;
        border: solid white;
        border-width: 0 5px 5px 0;
        position: absolute;
        top: 25px;
        left: 32px;
        }

        .checkmark.draw:after {
        content: '';
        animation: checkmark 0.3s ease-in-out forwards;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        }

        @keyframes checkmark {
        0% {
            height: 0;
            width: 0;
            opacity: 1;
        }
        20% {
            height: 0;
            width: 15px;
            opacity: 1;
        }
        40% {
            height: 30px;
            width: 15px;
            opacity: 1;
        }
        100% {
            height: 30px;
            width: 15px;
            opacity: 1;
        }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Professor Availability</h2>
        <form action="submit_availability.php" method="post">
            <div class="form-group">
                <label for="name">Select Professor:</label>
                <select name="name" id="name" class="form-control">
                    <?php foreach ($professors as $professor): ?>
                        <option value="<?php echo htmlspecialchars($professor['name']); ?>">
                            <?php echo htmlspecialchars($professor['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="availability-container">
                <?php
                foreach ($weekDays as $weekNumber => $days) {
                    echo "<div class='week-row'>";
                    foreach ($days as $date) {
                        echo "<div class='date-container'>";
                        echo "<strong>$date</strong>";
                        foreach ($uniqueTimeslots[$date] as $timeslot) {
                            $inputName = "availability[$date][$timeslot]";
                            echo "<div class='checkbox timeslot'><label><input type='checkbox' name='$inputName'> <span class='timeslot-label'>$timeslot</span></label></div>";
                        }
                        echo "</div>";
                    }
                    echo "</div>";
                }
                ?>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Availability</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to submit your availability?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmSubmit">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="checkmark-circle">
                        <div class="background"></div>
                        <div class="checkmark draw"></div>
                    </div>
                    <h5>Your availability has been successfully submitted!</h5>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
    // Trigger confirmation modal on form submit
    $("form").submit(function(e) {
        e.preventDefault(); // Prevent actual form submission
        $("#confirmationModal").modal('show');
    });

    // Handle confirmation
    $("#confirmSubmit").click(function() {
        $("#confirmationModal").modal('hide');
        // Simulate form submission and show success modal
        setTimeout(function() {
        $("#successModal").modal('show');
        }, 500);
    });
    });
    </script>
</body>
</html>
