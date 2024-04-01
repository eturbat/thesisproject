<?php

// checks if a states parameter is present in the url. Based on the status, it prepares a message and an alert type to be displayed to the user. 
// this could be a result of adding, editing, or deleting a professor, or an error message.
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $alertType = '';
    $message = '';

    // switch statement to handle different status values and set the appropriate message and alert type
    switch ($status) {
        // cases for different statuses, setting the appropriate alert type and message
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

    // displays the alert message if one is set
    if ($message !== '') {
        echo "<div class='alert $alertType' role='alert'>$message</div>";
    }
}

// making a database connection
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

// check for database connection error
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// function to fetch all professors
// this function queries the 'professor_list' table in the database to retrieve all professor records and returns them as an array.
function fetchAllProfessors($mysqli) {
    $query = "SELECT * FROM professor_list";
    $result = $mysqli->query($query);

    $professors = []; // initializing an array to hold professor data
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $professors[] = $row; // adding each row to the professors array
        }
    }
    return $professors;
}

// checks if the request method is POST, indicating form submission. 
// if so, it processes the form data to add a new professor name to the 'professor_list' table. 
// upon successful insertion, it redirects to the 'admin_panel.php' page with a success status; otherwise, it redirects with an error status.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newProfessorName = $_POST['newProfessorName'];

    // Prepare and bind
    $stmt = $mysqli->prepare("INSERT INTO professor_list (name) VALUES (?)");
    $stmt->bind_param("s", $newProfessorName); // s indicates a string parameter

    if ($stmt->execute()) {
        // redirect back to professor availability page with success message
        header('Location: admin_panel.php?page=add_professor&status=professoradded');
    } else {
        // with error message
        header('Location: admin_panel.php?page=add_professor&status=professor_error');
    }

    $stmt->close();
}
// fetch all professors to be used later in calling them on interface (for example, to display in a list on the page)
$professors = fetchAllProfessors($mysqli);
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <title>Manage Professor</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .form-container {
            display: flex;
            justify-content: space-around;
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
                    <input type="text" name="newProfessorName" id="newProfessorName" class="form-control" placeholder="e.g. Dr. Sofia Visa" required>
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
