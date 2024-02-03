<?php
session_start();
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];

    // Adjusted to fetch the plain password instead of a hashed password
    $stmt = $mysqli->prepare("SELECT password FROM panel_passwords WHERE panel = 'student'");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Direct comparison instead of using password_verify()
    if ($row && $password === $row['password']) {
        $_SESSION['student_logged_in'] = true;
        header('Location: student_index.php');
        exit;
    } else {
        $error = 'Incorrect password.';
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Student Panel Login</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="student_login.php" method="post">
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
</body>
</html>
