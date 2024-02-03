<?php
// Assuming $mysqli is already connected

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $panel = $_POST['panel'];
    $password = $_POST['password'];

    // Check if the password for this panel already exists
    $checkQuery = "SELECT * FROM panel_passwords WHERE panel = ?";
    $stmt = $mysqli->prepare($checkQuery);
    $stmt->bind_param("s", $panel);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update the existing password
        $updateQuery = "UPDATE panel_passwords SET password = ? WHERE panel = ?";
    } else {
        // Insert a new password
        $updateQuery = "INSERT INTO panel_passwords (password, panel) VALUES (?, ?)";
    }

    $stmt = $mysqli->prepare($updateQuery);
    $stmt->bind_param("ss", $password, $panel);
    $stmt->execute();

    echo "<div class='alert alert-success'>Password for $panel panel updated successfully.</div>";
}

// Fetch current passwords
$fetchPasswordsQuery = "SELECT panel, password FROM panel_passwords";
$fetchStmt = $mysqli->prepare($fetchPasswordsQuery);
$fetchStmt->execute();
$fetchResult = $fetchStmt->get_result();
$currentPasswords = [];
while ($row = $fetchResult->fetch_assoc()) {
    $currentPasswords[$row['panel']] = $row['password'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .password-management-container {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            width: 60%; /* Adjusted width for better centering */
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
        .submit-btn {
            display: block;
            width: 100%;
            margin: 10px auto;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        .panel-form {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px; 
            margin: 0 10px;
        }
        .row.centered-forms {
            justify-content: center;
            display: flex;
            flex-wrap: wrap;
        }
    </style>
    <title>Password Management</title>
</head>
<body>
    <div class="container">
        <div class="password-management-container">
            <div class="form-header">Password Management</div>
            <div class="row centered-forms">
                <?php foreach (['student', 'professor'] as $panel): ?>
                <div class="col-md-6">
                    <form action="" method="post" class="panel-form">
                        <input type="hidden" name="panel" value="<?= $panel ?>">
                        <div class="form-group">
                            <label><?= ucfirst($panel) ?> Panel Password</label>
                            <input id="password-<?= $panel ?>" type="password" name="password" value="<?= htmlspecialchars($currentPasswords[$panel] ?? '') ?>" class="form-control" required>
                            <span toggle="#password-<?= $panel ?>" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        </div>
                        <button type="submit" class="submit-btn">Update</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    });
    </script>
</body>
</html>
