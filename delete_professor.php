<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

// Check for database connection error
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$professorId = $_GET['id'] ?? null;

// Delete professor
if ($professorId) {
    $stmt = $mysqli->prepare("DELETE FROM professor_list WHERE id = ?");
    $stmt->bind_param("i", $professorId);

    if ($stmt->execute()) {
        header('Location: admin_panel.php?page=add_professor&status=professordeleted');
    } else {
        header('Location: admin_panel.php?page=add_professor&status=professor_error');
    }

    $stmt->close();
    exit;
}
?>
