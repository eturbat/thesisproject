<?php
// verifying that the password for the session hasn't been changed since the user last logged in (for professors panel)
function validateSession($mysqli) {
    if (!isset($_SESSION['password_last_updated'])) {
        // no password last updated timestamp in session, force logout
        header('Location: professor_logout.php');
        exit;
    }

    // fetch the last updated timestamp for the student panel password
    $stmt = $mysqli->prepare("SELECT last_updated FROM panel_passwords WHERE panel = 'professor'");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && $_SESSION['password_last_updated'] != $row['last_updated']) {
        // password has been updated since user logged in, force logout
        header('Location: professor_logout.php');
        exit;
    }
}
?>