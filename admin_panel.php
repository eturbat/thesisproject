<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

function fetchAllBookings($mysqli, $selectedProfessor = '') {
    if (!empty($selectedProfessor) && $selectedProfessor != 'All') {
        $stmt = $mysqli->prepare("SELECT * FROM bookings WHERE reader_one = ? OR reader_two = ?");
        $stmt->bind_param('ss', $selectedProfessor, $selectedProfessor);
    } else {
        $stmt = $mysqli->prepare("SELECT * FROM bookings");
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    return $bookings;
}

$professorsQuery = "SELECT DISTINCT reader_one FROM bookings UNION SELECT DISTINCT reader_two FROM bookings ORDER BY reader_one";
$professorsResult = $mysqli->query($professorsQuery);

$professors = [];
while ($row = $professorsResult->fetch_assoc()) {
    $professors[] = $row['reader_one'];
}

$selectedProfessor = isset($_GET['professor']) ? $_GET['professor'] : 'All';
$bookings = fetchAllBookings($mysqli, $selectedProfessor);
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .navbar-nav {
            display: flex;
            justify-content: center; /* Center navbar items */
            width: 100%;
        }
        .navbar-nav li {
            margin: 0 10px; /* Equal margin on both sides */
        }
        .navbar-nav li a {
            border-radius: 4px;
        }
        .navbar-nav .active a {
            background-color: #5cb85c; /* Active item background color */
            color: white !important;
        }
        /* Ensure the navbar doesn't collapse in mobile view */
        @media (max-width: 767px) {
            .navbar-nav {
                flex-direction: row;
                flex-wrap: nowrap;
                overflow-x: auto;
            }
        }
        #bookingsTable {
            margin: 0px auto;
            width: 80%;
        }
        #bookingsTable table {
            font-size: 0.8em;
            border-collapse: collapse;
        }
        #bookingsTable th, #bookingsTable td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        #bookingsTable th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <ul class="nav navbar-nav">
        <img src="img/logo.png" alt="Logo" class="navbar-brand" href="https://wooster.edu/">
       
        <li class="<?= !isset($_GET['page']) ? 'active' : '' ?>">
            <a href="admin_panel.php">Home</a>
        </li>
        
        <li class="<?= (isset($_GET['page']) && $_GET['page'] == 'set_dates') ? 'active' : '' ?>">
            <a href="admin_panel.php?page=set_dates">Manage Dates</a>
        </li>

        <li class="<?= (isset($_GET['page']) && $_GET['page'] == 'room_availability') ? 'active' : '' ?>">
            <a href="admin_panel.php?page=room_availability">Manage Rooms</a>
        </li>

        <li class="<?= (isset($_GET['page']) && $_GET['page'] == 'add_professor') ? 'active' : '' ?>">
            <a href="admin_panel.php?page=add_professor">Manage Professors</a>
        </li>

        <li class="<?= (isset($_GET['page']) && $_GET['page'] == 'password_management') ? 'active' : '' ?>">
            <a href="admin_panel.php?page=password_management">Manage Passwords</a>
        </li>
        
        </ul>
      </div>
    </nav>

    <div class="container">
        <?php
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
                case 'set_dates':
                    include('set_dates.php');
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
            }
        } else {
            echo '<div id="bookingsTable" class="table-responsive">'; 
            echo 
            '<div class="control-row">
            <form action="" method="get" class="form-inline" style="display: flex; align-items: center;">
                <h4 style="margin-top: 20px; margin-bottom: 20px;">Computer Science Department Oral Defense Schedule for</h4>
                <div class="form-group">
                    <select name="professor" id="professor" class="form-control" onchange="this.form.submit()" style="margin-top: 20px; margin-bottom: 20px; margin-left: 10px;">
                        <option value="All">All Professors</option>';     
                        foreach ($professors as $professor) {
                            $selected = ($selectedProfessor == $professor) ? ' selected' : '';
                            echo "<option value=\"$professor\"$selected>$professor</option>";
                        }
                        echo '
                        </select>
                    </div>
                </form>
            </div>';            
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
            #echo '<th>Thesis Topic</th>';

            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            foreach ($bookings as $booking) {
                echo '<tr>';
                echo '<td>'.htmlspecialchars($booking['name']).'</td>';
                echo '<td>'.htmlspecialchars($booking['email']).'</td>';
                echo '<td>'.htmlspecialchars($booking['date']).'</td>';
                echo '<td>'.htmlspecialchars($booking['timeslot']).'</td>';
                echo '<td>'.htmlspecialchars($booking['room']).'</td>';
                echo '<td>'.htmlspecialchars($booking['reader_one']).'</td>';
                echo '<td>'.htmlspecialchars($booking['reader_two']).'</td>';
                #echo '<td>'.htmlspecialchars($booking['thesis']).'</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            echo '
            <div class="col-md-11 text-right" style="margin-left: 25px;">
                <button onclick="generatePDF()" class="btn btn-primary">Download PDF</button>
            </div>';

        }
        ?>
    </div>
    <script>
    function generatePDF() {
        const element = document.getElementById("bookingsTable");
        html2pdf().from(element).set({
            margin: 0,
            filename: 'bookings-list.pdf',
            html2canvas: { scale: 15 },
            jsPDF: { orientation: 'landscape', unit: 'in', format: 'letter', compressPDF: true }
        }).save();
    }
    </script>
</body>
</html>
