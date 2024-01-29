<?php

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'professorlist_updated') {
        echo "<p class='alert alert-success'>Professor updated successfully!</p>";
    }
}

$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

// Check for database connection error
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$professorId = $_GET['id'] ?? null;
$professorName = '';

// Fetch professor details
if ($professorId) {
    $stmt = $mysqli->prepare("SELECT * FROM professor_list WHERE id = ?");
    $stmt->bind_param("i", $professorId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $professorName = $row['name'];
    }
    $stmt->close();
}

// Update professor details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $professorId = $_POST['id'];
    $updatedName = $_POST['name'];

    $stmt = $mysqli->prepare("UPDATE professor_list SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $updatedName, $professorId);

    if ($stmt->execute()) {
        header('Location: admin_panel.php?page=add_professor&status=professoredited');
    } else {
        header('Location: admin_panel.php?page=add_professor&status=professor_error');
    }

    $stmt->close();
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <title>Edit Professor</title>
</head>
<body>
    <div class="container">
        <h2>Edit Professor</h2>
        <form action="edit_professor.php" method="post">
            <input type="hidden" name="id" value="<?php echo $professorId; ?>">
            <div class="form-group">
                <label for="name">Professor Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($professorName); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
