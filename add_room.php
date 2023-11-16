<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

// Check for database connection error
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newRoomName = $_POST['newRoomName'];

    // Prepare and bind
    $stmt = $mysqli->prepare("INSERT INTO available_rooms (room_name) VALUES (?)");
    $stmt->bind_param("s", $newRoomName);

    if ($stmt->execute()) {
        // Redirect back to room availability page with success message
        header('Location: admin_panel.php?page=room_availability&status=roomadded');
    } else {
        // Handle error
        header('Location: admin_panel.php?page=room_availability.php?status=room_error');
    }

    $stmt->close();
}

$mysqli->close();
?>
