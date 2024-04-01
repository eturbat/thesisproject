<?php
//db connection
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$professorId = $_GET['id'] ?? null;

if ($professorId) {
    // first, fetch the professor's name to delete associated availabilities
    $professorNameQuery = "SELECT name FROM professor_list WHERE id = ?";
    $stmt = $mysqli->prepare($professorNameQuery);
    $stmt->bind_param("i", $professorId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($professor = $result->fetch_assoc()) {
        $professorName = $professor['name'];

        // begin transaction
        $mysqli->begin_transaction();

        try {
            // delete professor availability timeslots
            $deleteTimeslotsQuery = "DELETE FROM professors WHERE name = ?";
            $stmtTimeslots = $mysqli->prepare($deleteTimeslotsQuery);
            $stmtTimeslots->bind_param("s", $professorName);
            $stmtTimeslots->execute();
            $stmtTimeslots->close();

            // delete the professor from professor_list
            $deleteProfessorQuery = "DELETE FROM professor_list WHERE id = ?";
            $stmtProfessor = $mysqli->prepare($deleteProfessorQuery);
            $stmtProfessor->bind_param("i", $professorId);
            $stmtProfessor->execute();
            $stmtProfessor->close();

            // commit transaction
            $mysqli->commit();

            // redirect back to manage professor page with success message
            header('Location: admin_panel.php?page=add_professor&status=professordeleted');
            exit;
        } catch (mysqli_sql_exception $exception) {
            $mysqli->rollback();
            // redirect back with an error message
            header('Location: admin_panel.php?page=add_professor&status=professor_error');
            exit;
        }
    } else {
        // redirect back with an error message if professor not found
        header('Location: admin_panel.php?page=add_professor&status=professor_error');
        exit;
    }
}
?>
