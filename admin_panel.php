<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

function fetchAllBookings($mysqli) {
    $query = "SELECT * FROM bookings";
    $result = $mysqli->query($query);

    $bookings = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
    }
    return $bookings;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <title>Admin Panel</title>
    <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
    <style>
        #bookingsTable {
            margin: 20px auto; /* Center table and add margin */
            width: 95%; /* Adjust width */
        }
        #bookingsTable table {
            font-size: 0.8em; /* Make table text smaller */
            border-collapse: collapse; /* Collapse borders */
        }
        #bookingsTable th, #bookingsTable td {
            padding: 8px; /* Add padding */
            border: 1px solid #ddd; /* Add border */
        }
        #bookingsTable th {
            background-color: #f2f2f2; /* Header background color */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Admin Panel</a>
        </div>
        <ul class="nav navbar-nav">
          <li class="active"><a href="admin_panel.php">Home</a></li>
          <li><a href="admin_panel.php?page=date_range_picker">Date Range Picker</a></li>
          <li><a href="admin_panel.php?page=room_availability">Room Availability</a></li>
          <li><a href="admin_panel.php?page=add_professor">Add Professor</a></li>
          <li><a href="admin_panel.php?page=password_management">Password Management</a></li>
        </ul>
      </div>
    </nav>

    <div class="container">
        <?php
        if (isset($_GET['status'])) {
            $status = $_GET['status'];
            $alertType = '';
            $message = '';

            switch ($status) {
                case 'success':
                    $alertType = 'alert-success';
                    $message = 'Dates set successfully!';
                    break;
                case 'invalid':
                    $alertType = 'alert-warning';
                    $message = 'Invalid dates provided!';
                    break;
                case 'error':
                    $alertType = 'alert-danger';
                    $message = 'An error occurred while setting dates!';
                    break;
            }

            if ($message !== '') {
                echo "<div class='alert $alertType' role='alert'>$message</div>";
            }
        }
        
        if (isset($_GET['roomStatus'])) {
            $roomStatus = $_GET['roomStatus'];
            $alertType = '';
            $message = '';
        
            switch ($roomStatus) {
                case 'success':
                    $alertType = 'alert-success';
                    $roomName = isset($_GET['roomName']) ? urldecode($_GET['roomName']) : 'the room';
                    $message = "Availability for $roomName set successfully!";
                    break;
                case 'error':
                    $alertType = 'alert-danger';
                    $message = 'An error occurred while setting room availability!';
                    break;
            }
        
            if ($message !== '') {
                echo "<div class='alert $alertType' role='alert'>$message</div>";
            }
        }

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            switch ($page) {
                case 'date_range_picker':
                    include('date_range_picker.php');
                    break;
                case 'room_availability':
                    include('room_availability.php');
                    break;
                case 'add_professor':
                    include('add_professor.php');
                    break;
                case 'password_management':
                    include('password_management.php');
                    break;
                default:
                    echo "<h3>Welcome to the Admin Panel</h3>";
                    break;
            }
        } else {
            echo '<div id="bookingsTable" class="table-responsive">'; 

            echo "<h3>Welcome to the Admin Panel</h3>";
            
            $allBookings = fetchAllBookings($mysqli);
            echo '<table class="table table-bordered">';
            echo '<thead>';
            echo '<tr>';

            echo '<th>Name</th>';
            echo '<th>Email</th>';
            echo '<th>Date</th>';
            echo '<th>Time Slot</th>';
            echo '<th>Room</th>';
            echo '<th>First Reader</th>';
            echo '<th>Second Reader</th>';
            echo '<th>Thesis Topic</th>';

            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            foreach ($allBookings as $booking) {
                echo '<tr>';
                echo '<td>'.htmlspecialchars($booking['name']).'</td>';
                echo '<td>'.htmlspecialchars($booking['email']).'</td>';
                echo '<td>'.htmlspecialchars($booking['date']).'</td>';
                echo '<td>'.htmlspecialchars($booking['timeslot']).'</td>';
                echo '<td>'.htmlspecialchars($booking['room']).'</td>';
                echo '<td>'.htmlspecialchars($booking['reader_one']).'</td>';
                echo '<td>'.htmlspecialchars($booking['reader_two']).'</td>';
                echo '<td>'.htmlspecialchars($booking['thesis']).'</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        }
        ?>
        <button onclick="generatePDF()">Download PDF</button>
    </div>
    <script>
        function generatePDF() {
            const element = document.getElementById("bookingsTable");
            html2pdf().from(element).save("bookings-list.pdf");
        }
    </script>
</body>
</html>
