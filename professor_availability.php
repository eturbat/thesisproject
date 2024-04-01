<?php
// session to manage user sessions throughout the app
session_start();

// redirect to login page if the professor is not logged in
if (!isset($_SESSION['professor_logged_in'])) {
    header('Location: professor_login.php');
    exit;
}

// db connection
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

// includes and calls a function from an external script (professor_sessionValidator.php) to validate the user's session 
require_once "professor_sessionValidator.php";
validateSession($mysqli);

// Handle form submission for setting availability
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $availability = $_POST["availability"] ?? [];

    // sql for inserting availability data
    $stmt = $mysqli->prepare("INSERT INTO professors (name, date, timeslot) VALUES (?, ?, ?)");

    foreach ($availability as $date => $timeslots) {
        foreach ($timeslots as $timeslot => $value) {
            // Bind parameters and execute for each timeslot
            $stmt->bind_param("sss", $name, $date, $timeslot);
            if (!$stmt->execute()) {
                // Handle error appropriately
                echo "Error: " . $stmt->error;
            }
        }
    }
    $stmt->close();

    // redirect to a success page after handling the submission
    header('Location: professor_availability_success.php');
    exit;
}

// fetch the oral defense timeframe from the database to build prof availability form
$query = "SELECT start_date, end_date FROM defense_schedule ORDER BY id DESC LIMIT 1";
$result = $mysqli->query($query);

// check if there are any retrieved dates and set them for use
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $start_date = new DateTime($row['start_date']);
    $end_date = new DateTime($row['end_date']);
    $end_date->modify('+1 day'); // to include the end date in the range (because it excludes last day)
} else {
    // exit if no defense schedule dates are set
    echo "No defense schedule dates are set.";
    exit;
}

// fetch list of professors
$profQuery = "SELECT * FROM professor_list";
$profResult = $mysqli->query($profQuery);

$professors = [];
if ($profResult) {
    while ($row = $profResult->fetch_assoc()) {
        $professors[] = $row;
    }
}

// fetch unique available timeslots across all rooms within the defense schedule dates
$uniqueTimeslotsQuery = "SELECT DISTINCT date, timeslot FROM rooms WHERE date BETWEEN ? AND ?";
$uniqueTimeslotsStmt = $mysqli->prepare($uniqueTimeslotsQuery);
$startDateStr = $start_date->format('Y-m-d');
$endDateStr = $end_date->format('Y-m-d');
$uniqueTimeslotsStmt->bind_param("ss", $startDateStr, $endDateStr);
$uniqueTimeslotsStmt->execute();
$uniqueTimeslotsResult = $uniqueTimeslotsStmt->get_result();

$uniqueTimeslots = [];
while ($row = $uniqueTimeslotsResult->fetch_assoc()) {
    $uniqueTimeslots[$row['date']][] = $row['timeslot']; // organize timeslots by date
}
$uniqueTimeslotsStmt->close();

// sort the timeslots for each date for better slot readability
foreach ($uniqueTimeslots as &$timeslots) {
    usort($timeslots, function($a, $b) {
        return strtotime(explode('-', $a)[0]) - strtotime(explode('-', $b)[0]);
    });
}
unset($timeslots); // break the reference with the last element

// organize dates into weeks, excluding weekends
$period = new DatePeriod($start_date, new DateInterval('P1D'), $end_date);
$weekDays = [];
foreach ($period as $date) {
    if ($date->format('N') < 6) { // check if the day is a weekday
        $weekDays[$date->format("W")][] = $date->format("Y-m-d"); // grouping by week
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <title>Professor Availability</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .availability-container {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            width: 90%; 
            margin: 20px auto;
        }
        .week-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .date-container {
            flex-grow: 1;
            background-color: #fff;
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
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .form-group {
            text-align: left; 
            margin-top: 30px;
            width: 40%; 
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
        .instruction-box {
            border: 1px solid #f39c12;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #fff5f5;
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
        .divider {
            border-top: 1px solid #ccc;
            margin: 15px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="availability-container">
        <h2>Professor Availability Poll</h2>
            <div class="instruction-box">
                <p><strong>Welcome to the Oral Defense Scheduling System!</strong> please mark your available slots below. 
                Your availability information is important for generating a calendar that students can use to schedule their oral defense sessions. 
                This process simplifies scheduling for oral defense coordinators, readers, and defenders, ensuring a smooth and efficient coordination of oral defenses. 
                </p>
                <p><strong>Please ensure that you accurately mark all times when you are available to participate in defenses. </strong></p>
            </div>
        <hr class="divider">
        <form action="professor_availability.php" method="post">
            <div class="form-group">
                <select name="name" id="name" class="form-control">
                    <option value="">Select your name</option> <!-- Default option -->
                    <?php foreach ($professors as $professor): ?>
                        <option value="<?php echo htmlspecialchars($professor['name']); ?>">
                            <?php echo htmlspecialchars($professor['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php
            foreach ($weekDays as $weekNumber => $days) {
                echo "<div class='week-row'>";
                foreach ($days as $date) {
                    
                    $dateObj = new DateTime($date);
                    $displayDate = $dateObj->format("D, d F"); // formatting the date to show as: ex. Wed, 03 April
            
                    echo "<div class='date-container'>";
                    echo "<strong>$displayDate</strong>";
                    foreach ($uniqueTimeslots[$date] as $timeslot) {
                        $inputName = "availability[$date][$timeslot]"; // Keep the submission format as Y-m-d
                        echo "<div class='checkbox timeslot'><label><input type='checkbox' name='$inputName'> <span class='timeslot-label'>$timeslot</span></label></div>";
                    }
                    echo "</div>";
                }
                echo "</div>";
            }
            ?>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to submit your availability? Please double-check your selections. Once submitted, changes may not be possible.');">Submit</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>

