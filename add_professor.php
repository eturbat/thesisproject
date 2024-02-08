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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <title>Manage Professor</title>
    <style>
        .form-container {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        .form-section {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            width: 45%; 
            margin-bottom: 20px;
            margin-right: 20px;
        }
        .add-professor-btn {
            display: inline-block;
            width: 35px;
            height: 35px;
            background-color: #007bff;
            color: white;
            text-align: center;
            line-height: 30px;
            border-radius: 5px;
            cursor: pointer;
        }
        .add-professor-input {
            display: inline-block;
            width: calc(100% - 45px);
        }
        .delete-icon {
            color: #d9534f; 
            cursor: pointer;
        }
        .delete-icon i.fa, .delete-icon i.fas {
            font-size: 1.5rem; 
            margin-left: auto;
        }
        .list-group-item {
            display: flex;
            justify-content: space-between;
        }
        .divider {
            border-top: 1px solid #ccc;
            margin: 15px 0;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="form-section">
                <form action="add_professor.php" method="post" class="d-flex align-items-center" onsubmit="return validateForm();">
                    <div class="form-group add-professor-input">
                    <label for="newProfessorName">Add Professor</label>
                    <input type="text" name="newProfessorName" id="newProfessorName" class="form-control" placeholder="ex. Dr. Sofia Visa" required>
                </div>
                <button type="submit" class="add-professor-btn"><i class="fa fa-plus"></i></button>
            </form>
            <hr class="divider">
            <ul class="list-group">
                <?php foreach ($professors as $professor): ?>
                    <li class="list-group-item">
                        <?php echo htmlspecialchars($professor['name']); ?>
                        <a href="delete_professor.php?id=<?php echo $professor['id']; ?>" class="delete-icon" onclick="return confirm('Are you sure you want to delete this professor?');">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    </div>
</body>
</html>
