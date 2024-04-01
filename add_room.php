<?php
// db connection
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// processes form data if the request method is POST. 
// this is responsible for inserting a new room name into the 'available_rooms' table.  
// after attempting to insert the data, it redirects the user to a relevant page, either indicating success or an error based on the outcome of the insert operation.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newRoomName = $_POST['newRoomName'];

    // prepare and bind
    $stmt = $mysqli->prepare("INSERT INTO available_rooms (room_name) VALUES (?)");
    $stmt->bind_param("s", $newRoomName); // s indicates a string parameter

    if ($stmt->execute()) {
        // redirect back to room availability page with success message
        header('Location: admin_panel.php?page=room_availability&status=roomadded');
    } else {
        // handle error
        header('Location: admin_panel.php?page=room_availability?status=room_error');
    }

    $stmt->close();
}

$mysqli->close();
?>
