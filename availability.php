<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

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

$profQuery = "SELECT * FROM professor_list";
$profResult = $mysqli->query($profQuery);

$professors = [];
if ($profResult) {
    while ($row = $profResult->fetch_assoc()) {
        $professors[] = $row;
    }
}

$timeslots = ["09:00-09:50", "10:00-10:50", "11:00-11:50", "12:00-12:50", "13:00-13:50", "14:00-14:50", "15:00-15:50", "16:00-16:50"];
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
        .date-heading {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
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
                $period = new DatePeriod($start_date, new DateInterval('P1D'), $end_date);
                foreach ($period as $date) {
                    // Check if the day is Saturday or Sunday
                    if ($date->format('N') >= 6) {
                        continue; // Skip the weekend
                    }

                    $formattedDate = $date->format("Y-m-d");
                    echo "<div class='date-heading'><strong>$formattedDate</strong></div>";
                    foreach ($timeslots as $timeslot) {
                        $inputName = "availability[$formattedDate][$timeslot]";
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
