<?php

if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $alertType = '';
    $message = '';

    switch ($status) {
        case 'professoradded':
            $alertType = 'alert-success';
            $message = 'Professor added successfully!';
            break;
        case 'professoredited':
            $alertType = 'alert-success';
            $message = 'Professor edited successfully!';
            break;
        case 'professordeleted':
            $alertType = 'alert-success';
            $message = 'Professor deleted successfully!';
            break;
        case 'professor_error':
            $alertType = 'alert-danger';
            $message = 'There was an error processing your request.';
            break;
    }

    if ($message !== '') {
        echo "<div class='alert $alertType' role='alert'>$message</div>";
    }
}

$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

// Check for database connection error
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Function to fetch all professors
function fetchAllProfessors($mysqli) {
    $query = "SELECT * FROM professor_list";
    $result = $mysqli->query($query);

    $professors = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $professors[] = $row;
        }
    }
    return $professors;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newProfessorName = $_POST['newProfessorName'];

    // Prepare and bind
    $stmt = $mysqli->prepare("INSERT INTO professor_list (name) VALUES (?)");
    $stmt->bind_param("s", $newProfessorName);

    if ($stmt->execute()) {
        // Redirect back to professor availability page with success message
        header('Location: admin_panel.php?page=add_professor&status=professoradded');
    } else {
        // Handle error
        header('Location: admin_panel.php?page=add_professor&status=professor_error');
    }

    $stmt->close();
}

$professors = fetchAllProfessors($mysqli);

?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <title>Manage Professor</title>
    <style>
        .form-section {
            margin-top: 20px;
        }
        .form-section button {
            margin-bottom: 100px;
            float: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Professors</h2>

        <div class="form-section">
            <form action="add_professor.php" method="post">
                <div class="form-group">
                    <label for="newProfessorName">Add Professor (ex. Dr. Visa):</label>
                    <input type="text" name="newProfessorName" id="newProfessorName" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Professor</button>
            </form>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($professors as $professor): ?>
                    <tr>
                        <td><?php echo $professor['id']; ?></td>
                        <td><?php echo htmlspecialchars($professor['name']); ?></td>
                        <td>
                            <a href="edit_professor.php?id=<?php echo $professor['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_professor.php?id=<?php echo $professor['id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>