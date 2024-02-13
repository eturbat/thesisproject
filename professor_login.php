<?php
session_start();
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];

    // Adjusted to fetch the plain password instead of a hashed password
    $stmt = $mysqli->prepare("SELECT password FROM panel_passwords WHERE panel = 'professor'");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Direct comparison instead of using password_verify()
    if ($row && $password === $row['password']) {
        $_SESSION['professor_logged_in'] = true;
        header('Location: professor_availability.php');
        exit;
    } else {
        $error = 'Incorrect password.';
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Professor Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .password-management-container {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            width: 40%;
            margin-left: auto;
            margin-right: auto;
        }
        .form-header {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #333;
        }
        .field-icon {
            float: right;
            left: -10px;
            margin-left: -25px;
            margin-top: -25px;
            position: relative;
            z-index: 2;
        }
        .btn {
            display: block;
            width: 100%;
            margin: 10px auto;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .panel-form {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px; 
            margin: 0 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="password-management-container">
        <div class="form-header"><strong>Professor Login</strong></div>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="professor_login.php" method="post" class="panel-form">
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
                <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('.toggle-password').click(function() {
        $(this).toggleClass('fa-eye fa-eye-slash');
        var input = $($(this).attr('toggle'));
        if (input.attr('type') == 'password') {
            input.attr('type', 'text');
        } else {
            input.attr('type', 'password');
        }
    });
});
</script>
</body>
</html>
