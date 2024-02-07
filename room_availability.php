<?php

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'roomadded') {
        echo "<p class='alert alert-success'>New room added successfully!</p>";
    } elseif ($_GET['status'] == 'room_error') {
        echo "<p class='alert alert-danger'>There was an error adding the room.</p>";
    } elseif ($_GET['status'] == 'roomdeleted') {
        echo "<p class='alert alert-success'>Room deleted successfully!</p>";
    }
}

$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

if (isset($_GET['delete'])) {
    $roomId = $_GET['delete'];

    // First, fetch the room name to delete associated timeslots
    $roomNameQuery = "SELECT room_name FROM available_rooms WHERE room_id = ?";
    $stmt = $mysqli->prepare($roomNameQuery);
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($room = $result->fetch_assoc()) {
        $roomName = $room['room_name'];

        // Begin transaction
        $mysqli->begin_transaction();

        try {
            // Delete room availability timeslots
            $deleteTimeslotsQuery = "DELETE FROM rooms WHERE name = ?";
            $stmtTimeslots = $mysqli->prepare($deleteTimeslotsQuery);
            $stmtTimeslots->bind_param("s", $roomName);
            $stmtTimeslots->execute();
            $stmtTimeslots->close();

            // Delete the room
            $deleteRoomQuery = "DELETE FROM available_rooms WHERE room_id = ?";
            $stmtRoom = $mysqli->prepare($deleteRoomQuery);
            $stmtRoom->bind_param("i", $roomId);
            $stmtRoom->execute();
            $stmtRoom->close();

            // Commit transaction
            $mysqli->commit();

            // Redirect back to room_availability.php with success message
            header('Location: admin_panel.php?page=room_availability&status=roomdeleted');
            exit;
        } catch (mysqli_sql_exception $exception) {
            $mysqli->rollback();
            // Redirect back with an error message
            header('Location: admin_panel.php?page=room_availability&status=room_error');
            exit;
        }
    } else {
        // Redirect back with an error message if room not found
        header('Location: admin_panel.php?page=room_availability&status=room_error');
        exit;
    }
}

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <title>Room Availability</title>
    <style>
        .form-container {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        .form-section {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            width: 25%; 
            margin-bottom: 20px;
            margin-right: 20px;
        }
        .availability-container {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            width: 100%; 
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
        .add-room-btn {
            display: inline-block;
            width: 35px;
            height: 35px;
            background-color: #007bff; /* Bootstrap primary color */
            color: white;
            text-align: center;
            line-height: 30px; /* Vertically center the "+" */
            border-radius: 5px;
            cursor: pointer;
        }
        .add-room-input {
            display: inline-block;
            width: calc(100% - 45px); /* Adjust width to fit button */
        }
        .delete-icon i.fa, .delete-icon i.fas {
            font-size: 1.5rem; 
            margin-left: auto; /* Aligns the icon to the right */
        }
        .list-group-item {
            display: flex;
            justify-content: space-between; /* Ensures space between room name and icon */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="form-section">
                <form action="add_room.php" method="post" class="d-flex align-items-center" onsubmit="return validateForm();">
                    <div class="form-group add-room-input">
                        <label>Add Room</label>
                        <input type="text" name="newRoomName" id="newRoomName" class="form-control" placeholder="ex. Taylor Hall 200" required>
                    </div>
                    <button type="submit" class="add-room-btn"><i class="fa fa-plus"></i></button>
                </form>
                <hr>
                <ul class="list-group">
                    <label>Current Rooms</label>
                    <?php foreach ($rooms as $room): ?>
                        <li class="list-group-item">
                            <?php echo htmlspecialchars($room['room_name']); ?>
                            <a href="room_availability.php?delete=<?php echo $room['room_id']; ?>" onclick="return confirm('Are you sure you want to delete this room?');" class="delete-icon">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        

            <div class="availability-container">
                <form action="submit_room_availability.php" method="post">
                    <div class="form-group">
                        <label for="room">Select Room</label>
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
                                echo "</div>";
                            }
                            echo "</div>";
                        }
                        ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>