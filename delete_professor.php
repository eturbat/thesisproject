<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

// Check for database connection error
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$professorId = $_GET['id'] ?? null;

if ($professorId) {
    // First, fetch the professor's name to delete associated availabilities
    $professorNameQuery = "SELECT name FROM professor_list WHERE id = ?";
    $stmt = $mysqli->prepare($professorNameQuery);
    $stmt->bind_param("i", $professorId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($professor = $result->fetch_assoc()) {
        $professorName = $professor['name'];

        // Begin transaction
        $mysqli->begin_transaction();

        try {
            // Delete professor availability timeslots
            $deleteTimeslotsQuery = "DELETE FROM professors WHERE name = ?";
            $stmtTimeslots = $mysqli->prepare($deleteTimeslotsQuery);
            $stmtTimeslots->bind_param("s", $professorName);
            $stmtTimeslots->execute();
            $stmtTimeslots->close();

            // Delete the professor from professor_list
            $deleteProfessorQuery = "DELETE FROM professor_list WHERE id = ?";
            $stmtProfessor = $mysqli->prepare($deleteProfessorQuery);
            $stmtProfessor->bind_param("i", $professorId);
            $stmtProfessor->execute();
            $stmtProfessor->close();

            // Commit transaction
            $mysqli->commit();

            // Redirect back to manage professor page with success message
            header('Location: admin_panel.php?page=add_professor&status=professordeleted');
            exit;
        } catch (mysqli_sql_exception $exception) {
            $mysqli->rollback();
            // Redirect back with an error message
            header('Location: admin_panel.php?page=add_professor&status=professor_error');
            exit;
        }
    } else {
        // Redirect back with an error message if professor not found
        header('Location: admin_panel.php?page=add_professor&status=professor_error');
        exit;
    }
}
?>
