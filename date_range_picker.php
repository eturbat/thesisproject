<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
    <form action="set_dates.php" method="post">
        <label for="from">Start date:</label>
        <input type="text" id="from" name="start_date">
        <label for="to">End date:</label>
        <input type="text" id="to" name="end_date">
        <input type="submit" value="Set Dates">
    </form>
</body>
</html>
