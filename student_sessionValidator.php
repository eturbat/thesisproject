<?php
function validateSession($mysqli) {
    if (!isset($_SESSION['password_last_updated'])) {
        // No password last updated timestamp in session, force logout
        header('Location: student_logout.php');
        exit;
    }

    // Fetch the last updated timestamp for the student panel password
    $stmt = $mysqli->prepare("SELECT last_updated FROM panel_passwords WHERE panel = 'student'");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && $_SESSION['password_last_updated'] != $row['last_updated']) {
        // Password has been updated since user logged in, force logout
        header('Location: student_logout.php');
        exit;
    }
}
?>