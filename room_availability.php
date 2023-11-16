<?php

if (isset($_GET['status'])) {
  if ($_GET['status'] == 'roomadded') {
      echo "<p class='alert alert-success'>New room added successfully!</p>";
  } elseif ($_GET['status'] == 'room_error') {
      echo "<p class='alert alert-danger'>There was an error adding the room.</p>";
  }
}

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

$roomQuery = "SELECT * FROM available_rooms";
$roomResult = $mysqli->query($roomQuery);

$rooms = [];
if ($roomResult) {
    while ($row = $roomResult->fetch_assoc()) {
        $rooms[] = $row;
    }
}

$timeslots = ["09:00-09:50", "10:00-10:50", "11:00-11:50", "12:00-12:50", "13:00-13:50", "14:00-14:50", "15:00-15:50", "16:00-16:50"];
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <title>Room Availability</title>
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
        .form-section {
            margin-bottom: 100px;
        }
        .form-section:last-child {
            margin-bottom: 0;
        }
        .form-section button {
            margin-top: 10px;
            float: right;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Room Availability</h2>

        <div class="form-section">
            <form action="add_room.php" method="post">
                <div class="form-group">
                    <label for="newRoomName">Add Room (ex. Taylor Hall 200):</label>
                    <input type="text" name="newRoomName" id="newRoomName" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add</button>
            </form>
        </div>

        <div class="form-section clearfix">
            <form action="submit_room_availability.php" method="post">
                <div class="form-group">
                    <label for="room">Select Room:</label>
                    <select name="room" id="room" class="form-control">
                        <?php foreach ($rooms as $room): ?>
                            <option value="<?php echo htmlspecialchars($room['room_id']); ?>">
                                <?php echo htmlspecialchars($room['room_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="availability-container">
                    <?php
                    $period = new DatePeriod($start_date, new DateInterval('P1D'), $end_date);
                    $weekDays = [];
                    foreach ($period as $date) {
                        if ($date->format('N') >= 6) {
                            continue;
                        }

                        $weekDays[$date->format("W")][] = $date;
                    }

                    foreach ($weekDays as $weekNumber => $days) {
                        echo "<div class='week-row'>";
                        foreach ($days as $date) {
                            $formattedDate = $date->format("Y-m-d");
                            echo "<div class='date-container'>";
                            echo "<strong>$formattedDate</strong>";
                            foreach ($timeslots as $timeslot) {
                                $inputName = "availability[$formattedDate][$timeslot]";
                                echo "<div class='checkbox timeslot'><label><input type='checkbox' name='$inputName'> <span class='timeslot-label'>$timeslot</span></label></div>";
                            }
                            echo "</div>"; // Close date container
                        }
                        echo "</div>"; // Close week row
                    }
                    ?>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
