<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roomId = $_POST["room"];
    $availability = $_POST["availability"];

    // Fetch the room name using the room ID
    $roomStmt = $mysqli->prepare("SELECT room_name FROM available_rooms WHERE room_id = ?");
    $roomStmt->bind_param("i", $roomId);
    $roomStmt->execute();
    $result = $roomStmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $roomName = $row['room_name'];
    } else {
        header("Location: admin_panel.php?page=room_availability&roomStatus=error");
        exit;
    }
    $roomStmt->close();

    // Insert availability data
    $errorOccurred = false;
    foreach ($availability as $date => $timeslots) {
        foreach ($timeslots as $timeslot => $value) {
            $stmt = $mysqli->prepare("INSERT INTO rooms (name, date, timeslot) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $roomName, $date, $timeslot);
            if (!$stmt->execute()) {
                $errorOccurred = true;
                break;
            }
            $stmt->close();
        }
        if ($errorOccurred) {
            break;
        }
    }

    if (!$errorOccurred) {
        // URL encode the room name to ensure it's safe to pass in the URL
        $encodedRoomName = urlencode($roomName);
        header("Location: admin_panel.php?page=room_availability&roomStatus=success&roomName=$encodedRoomName");
    } else {
        header("Location: admin_panel.php?page=room_availability&roomStatus=error");
    }
}

$mysqli->close(); 
?>
