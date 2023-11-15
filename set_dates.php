<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

// Check for database connection error
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve form data
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

// Validate dates (basic example, consider more robust validation)
if (!$start_date || !$end_date || $start_date > $end_date) {
    // Handle invalid input
    header('Location: admin_panel.html?status=invalid');
    exit;
}

// SQL to insert or update the date range
$query = "INSERT INTO defense_schedule (start_date, end_date) VALUES (?, ?)
          ON DUPLICATE KEY UPDATE start_date = VALUES(start_date), end_date = VALUES(end_date)";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("ss", $start_date, $end_date);

if ($stmt->execute()) {
    // Redirect back to admin panel with success message
    header('Location: admin_panel.html?status=success');
} else {
    // Handle SQL error
    header('Location: admin_panel.html?status=error');
}

$stmt->close();
$mysqli->close();
?>
