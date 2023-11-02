<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room = $_POST["room"];
    $availability = $_POST["availability"];

    foreach ($availability as $date => $timeslots) {
        foreach ($timeslots as $timeslot => $value) {
            $stmt = $mysqli->prepare("INSERT INTO rooms (name, date, timeslot) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $room, $date, $timeslot);
            if (!$stmt->execute()) {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    echo "Room availability submitted successfully!";
}

$mysqli->close();
?>
