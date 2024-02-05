<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Date Range Picker</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <style>
        .date-picker-form {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            width: 50%; /* Adjust the width as needed */
            margin-left: auto;
            margin-right: auto;
        }
        .form-header {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #333;
        }
        .form-label {
            margin-right: 10px;
        }
        .form-control {
            margin-bottom: 10px;
            width: 100%; /* Adjust the width of input fields */
        }
        .btn {
            width: 100%; /* Button width to fit the form */
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
        .form-group {
            margin-bottom: 0; /* Remove bottom margin */
            width: 45%; /* Adjust the width of each input group */
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
</head>
<body>
    <div class="container">
        <div class="date-picker-form">
            <div class="form-header">Set Oral Defense Date</div>
            <form action="set_dates.php" method="post">
                <div class="date-inputs-row">
                    <div class="form-group">
                        <label for="from" class="form-label">Defense start date:</label>
                        <input type="text" id="from" name="start_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="to" class="form-label">Defense end date:</label>
                        <input type="text" id="to" name="end_date" class="form-control">
                    </div>
                </div>
                <input type="submit" value="Set Dates" class="btn btn-primary">
            </form>
        </div>
    </div>
</body>
</html>
