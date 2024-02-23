<?php

session_start();

if (!isset($_SESSION['student_logged_in'])) {
    header('Location: student_login.php');
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

require_once 'student_sessionValidator.php';
validateSession($mysqli);

// Fetch professors for dropdowns
$professors = [];
$result = $mysqli->query("SELECT DISTINCT name FROM professors");
while ($row = $result->fetch_assoc()) {
    $professors[] = $row['name'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <title>Schedule Oral Defense</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .instruction-box {
            border: 1px solid #f39c12;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #fff5f5;
        }
        .date-picker-form {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            width: 30%; 
            max-width: 600px; 
            margin-left: auto;
            margin-right: auto;
        }
        .form-header {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
            width: 100%;
        }
        label {
            display: block;
            margin-bottom: .5rem;
            font-weight: bold;
        }
        .form-control {
            display: block;
            width: 100%;
            padding: .375rem .75rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        .submit-btn-container {
            text-align: right;
        }
        .divider {
            border-top: 1px solid #ccc;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="date-picker-form">
        <div class="form-header"><strong>Let's schedule your oral defense!</strong></div>
        <hr class="divider">
            <div class="instruction-box">
                <p class="text-justify">
                <strong>Welcome to the Oral Defense Scheduling System!</strong> To schedule your oral defense, please fill out the form below with all the required information. 
                    <strong>You must select both your first and second readers from the dropdown menus</strong>. The system uses your selections to intersect the availability of both readers, 
                    generating a streamlined calendar for you to choose the most suitable timeslot for your defense. Please ensure that all information is accurate before submitting the form.
                </p>
            </div>
        <hr class="divider">
        <form action="schedule.php" method="get">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="e.g. Turbat Enkhtur" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="e.g. tenkhtur23@wooster.edu" required>
            </div>
            <div class="form-group">
                <label for="thesis">Thesis Topic:</label>
                <input type="text" class="form-control" id="thesis" name="thesis" placeholder="e.g. Oral Defense Scheduling Web App" required>
            </div>
            <div class="form-group">
                <label for="first_reader">First Reader:</label>
                <select class="form-control" id="first_reader" name="first_reader" required>
                <option value=""></option> <!-- Default option -->
                    <?php foreach($professors as $professor): ?>
                        <option value="<?php echo $professor; ?>"><?php echo $professor; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="second_reader">Second Reader:</label>
                <select class="form-control" id="second_reader" name="second_reader" required>
                <option value=""></option> <!-- Default option -->
                    <?php foreach($professors as $professor): ?>
                        <option value="<?php echo $professor; ?>"><?php echo $professor; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="submit-btn-container">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>
