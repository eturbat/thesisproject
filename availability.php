<?php
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
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
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
                foreach ($uniqueTimeslots as $date => $timeslots) {
                    echo "<div class='date-heading'><strong>$date</strong></div>";
                    foreach ($timeslots as $timeslot) {
                        $inputName = "availability[$date][$timeslot]";
                        echo "<div class='checkbox timeslot'><label><input type='checkbox' name='$inputName'> <span class='timeslot-label'>$timeslot</span></label></div>";
                    }
                }
                ?>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>