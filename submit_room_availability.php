<?php
// db connection
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// checks if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roomId = $_POST["room"];
    $availability = $_POST["availability"];

    // fetch the room name using the room id
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

    // insert availability data
    $errorOccurred = false;
    foreach ($availability as $date => $timeslots) {
        // convert the date from 'D, d F' format back to 'Y-m-d' format for insertion
        $dateObj = DateTime::createFromFormat("D, d F", $date);
        $formattedDate = $dateObj->format("Y-m-d");

        foreach ($timeslots as $timeslot => $value) {
            // insert availability data 
            $stmt = $mysqli->prepare("INSERT INTO rooms (name, date, timeslot) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $roomName, $formattedDate, $timeslot);
            if (!$stmt->execute()) {
                $errorOccurred = true; // Set the errorOccurred to true if an insertion fails
                break;
            }
            $stmt->close();
        }
        if ($errorOccurred) {
            break;
        }
    }

    if (!$errorOccurred) {
        // url encode the room name to ensure it's safe to pass in the url
        $encodedRoomName = urlencode($roomName);
        header("Location: admin_panel.php?page=room_availability&roomStatus=success&roomName=$encodedRoomName");
    } else {
        // show error message if any insertion failed
        header("Location: admin_panel.php?page=room_availability&roomStatus=error");
    }
}

$mysqli->close();
?>
