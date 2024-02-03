<?php

session_start();

if (!isset($_SESSION['student_logged_in'])) {
    header('Location: student_login.php');
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

// Fetch professors and rooms for dropdowns
$professors = [];
$result = $mysqli->query("SELECT DISTINCT name FROM professors");
while ($row = $result->fetch_assoc()) {
    $professors[] = $row['name'];
}

// $result = $mysqli->query("SELECT DISTINCT name FROM rooms");
// while ($row = $result->fetch_assoc()) {
//     $rooms[] = $row['name'];
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <title>Schedule Oral Defense</title>
</head>
<body>
    <div class="container">
        <h2>Oral Defense Scheduling</h2>
        <form action="schedule.php" method="get">
            <div class="form-group">
                <label for="thesis">Thesis Topic:</label>
                <input type="text" class="form-control" id="thesis" name="thesis" required>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="first_reader">First Reader:</label>
                <select class="form-control" id="first_reader" name="first_reader" required>
                    <?php foreach($professors as $professor): ?>
                        <option value="<?php echo $professor; ?>"><?php echo $professor; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="second_reader">Second Reader:</label>
                <select class="form-control" id="second_reader" name="second_reader" required>
                    <?php foreach($professors as $professor): ?>
                        <option value="<?php echo $professor; ?>"><?php echo $professor; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
