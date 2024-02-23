<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

// Check for database connection error
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $alertType = '';
    $message = '';

    switch ($status) {
        case 'date_success':
            $alertType = 'alert-success';
            $message = 'Dates set successfully!';
            break;
        case 'date_invalid':
            $alertType = 'alert-warning';
            $message = 'Invalid dates provided!';
            break;
        case 'date_error':
            $alertType = 'alert-danger';
            $message = 'An error occurred while setting dates!';
            break;
        case 'date_deleted':
            $alertType = 'alert-success';
            $message = 'Current date range deleted successfully!';
            break;
        case 'date_exists':
            $alertType = 'alert-warning';
            $message = 'To set a new date range, you must first delete the current date range.';
            break;

    }
}
// Fetch the current set date range
$currentDateQuery = "SELECT * FROM defense_schedule LIMIT 1";
$currentDateResult = $mysqli->query($currentDateQuery);
$currentDate = $currentDateResult->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a date range is already set
    if ($currentDate) {
        header('Location: admin_panel.php?page=set_dates&status=date_exists');
        exit;
    }

    // Retrieve form data
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validate dates
    if (!$start_date || !$end_date || $start_date > $end_date) {
        header('Location: admin_panel.php?page=set_dates&status=date_invalid');
        exit;
    } else {
        $query = "INSERT INTO defense_schedule (start_date, end_date) VALUES (?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ss", $start_date, $end_date);

        if ($stmt->execute()) {
            header('Location: admin_panel.php?page=set_dates&status=date_success');
        } else {
            header('Location: admin_panel.php?page=set_dates&status=date_error');
        }

        $stmt->close();
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    // Begin transaction to ensure data integrity
    $mysqli->begin_transaction();
    try {
        $tables = ['defense_schedule', 'bookings', 'available_rooms', 'professors', 'professor_list', 'rooms'];
        foreach ($tables as $table) {
            $query = "DELETE FROM $table";
            $mysqli->query($query);
        }

        // Commit the transaction if all deletions are successful
        $mysqli->commit();
        header('Location: admin_panel.php?page=set_dates&status=date_deleted');
        exit;
    } catch (mysqli_sql_exception $exception) {
        // Rollback the transaction in case of any error
        $mysqli->rollback();
        header('Location: admin_panel.php?page=set_dates&status=date_error');
        exit;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Date Range Picker</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .date-picker-form {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            width: 50%;
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
            margin-bottom: 0;
            width: 45%; 
        }
        .form-label {
            margin-right: 10px;
        }
        .form-control {
            margin-bottom: 10px;
            width: 100%;
        }
        .btn {
            width: 100%; 
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .date-inputs-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .entry-history-form {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            width: 50%;
            margin-left: auto;
            margin-right: auto;
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .delete-icon {
            color: #d9534f; 
            cursor: pointer;
        }

        .date-details {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }

        .date-label, .date-text {
            display: inline-block;
        }

        .delete-icon i.fa, .delete-icon i.fas {
            font-size: 2rem; 
        }
        .divider {
            border-top: 1px solid #ccc;
            margin: 15px 0;
        }
        
    </style>
</head>
<body>
<div class="container">
    <?php if (!empty($message)): ?>
        <div class="alert <?= $alertType; ?>"><?= $message; ?></div>
    <?php endif; ?>
    <div class="date-picker-form">
        <div class="form-header">Set Oral Defense Date</div>
            <form action="set_dates.php" method="post">
                <div class="date-inputs-row">
                    <div class="form-group">
                        <label for="from" class="form-label">Start date</label>
                        <input type="text" id="from" name="start_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="to" class="form-label">End date</label>
                        <input type="text" id="to" name="end_date" class="form-control">
                    </div>
                </div>
                <input type="submit" value="Set Dates" class="btn btn-primary">
            </form>
    </div>
    <?php if ($currentDate): ?>
    <div class="entry-history-form">
        <div class="form-header">
            <h4>Current Defense Date Range</h4>
            <a href="set_dates.php?action=delete" onclick="return confirm('Are you sure you want to delete this date range?');" class="delete-icon">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
            </a>
        </div>
        <hr class="divider">
        <div class="date-details">
            <span class="date-label">Start Date: </span><span class="date-text"><?= $currentDate['start_date']; ?></span>
        </div>
        <div class="date-details">
            <span class="date-label">End Date: </span><span class="date-text"><?= $currentDate['end_date']; ?></span>
        </div>
    </div>
    <?php endif; ?>
</div>
<script>
    $(function() {
        $("#from").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function(selectedDate) {
                $("#to").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#to").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function(selectedDate) {
                $("#from").datepicker("option", "maxDate", selectedDate);
            }
        });
    });
</script>
</body>
</html>