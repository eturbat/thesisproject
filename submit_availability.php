<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $availability = $_POST["availability"];

    foreach ($availability as $date => $timeslots) {
        foreach ($timeslots as $timeslot => $value) {
            $stmt = $mysqli->prepare("INSERT INTO professors (name, date, timeslot) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $date, $timeslot);
            if (!$stmt->execute()) {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    echo "Availability submitted successfully!";
}

$mysqli->close();
?>
