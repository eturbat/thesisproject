<?php
session_start();

if(isset($_GET['availableSlots'])) {
    $availableSlots = explode(',', $_GET['availableSlots']);
    echo "<pre>Available Slots: ";
    print_r($availableSlots);
    echo "</pre>";
} else {
    $availableSlots = [];
}

$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

require_once 'student_sessionValidator.php';
validateSession($mysqli);

$thesis = isset($_GET['thesis']) ? $_GET['thesis'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$first_reader = isset($_GET['first_reader']) ? $_GET['first_reader'] : '';
$second_reader = isset($_GET['second_reader']) ? $_GET['second_reader'] : '';

// Fetch intersected availability for selected professors
$intersectedAvailabilityQuery = "SELECT r.name, r.timeslot FROM rooms r
                                  JOIN professors p1 ON r.date = p1.date AND r.timeslot = p1.timeslot AND p1.name = ?
                                  JOIN professors p2 ON r.date = p2.date AND r.timeslot = p2.timeslot AND p2.name = ?
                                  WHERE r.date = ?";
$stmt = $mysqli->prepare($intersectedAvailabilityQuery);
$stmt->bind_param('sss', $first_reader, $second_reader, $date);
$stmt->execute();
$result = $stmt->get_result();

$roomsAvailability = [];
while ($row = $result->fetch_assoc()) {
    $roomsAvailability[$row['name']][] = $row['timeslot'];
}
$stmt->close();

$professorBookingsQuery = "SELECT timeslot FROM bookings WHERE date = ? AND (reader_one IN (?, ?) OR reader_two IN (?, ?))";
$stmt = $mysqli->prepare($professorBookingsQuery);
$stmt->bind_param('sssss', $date, $first_reader, $second_reader, $first_reader, $second_reader);
$stmt->execute();
$professorBookingsResult = $stmt->get_result();

$professorBookedSlots = [];
while ($row = $professorBookingsResult->fetch_assoc()) {
    $professorBookedSlots[] = $row['timeslot'];
}
$stmt->close();

$bookings = [];

// Fetch bookings for the selected date
$bookingsQuery = "SELECT room, timeslot FROM bookings WHERE date = ?";
$stmt = $mysqli->prepare($bookingsQuery);
$stmt->bind_param('s', $date);
$stmt->execute();
$bookingsResult = $stmt->get_result();

while ($row = $bookingsResult->fetch_assoc()) {
    $bookings[$row['room']][] = $row['timeslot'];
}
$stmt->close();


// // Remove booked slots from available slots
// foreach ($roomsAvailability as $roomName => &$timeslots) {
//     if (isset($bookedSlots[$roomName])) {
//         $timeslots = array_diff($timeslots, $bookedSlots[$roomName]);
//     }
// }

if(isset($_POST['submit'])){
    $thesis = $_POST['thesis'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $timeslot = $_POST['timeslot'];
    $date = $_POST['date'];
    $room = $_POST['room'];
    $reader_one = $_POST['reader_one'];
    $reader_two = $_POST['reader_two'];

    // Check if any of the selected professors are already booked for this timeslot
    $professorBookedQuery = "SELECT * FROM bookings WHERE date=? AND timeslot=? AND (reader_one=? OR reader_two=?)";
    $stmt = $mysqli->prepare($professorBookedQuery);
    $stmt->bind_param('ssss', $date, $timeslot, $reader_one, $reader_two);
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $msg = "<div class='alert alert-danger'>One or more professors are not available at this time.</div>";
        } else {
            // Proceed with booking
            $stmt = $mysqli->prepare("INSERT INTO bookings (name, email, date, timeslot, room, reader_one, reader_two, thesis) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->bind_param('ssssssss', $name, $email, $date, $timeslot, $room, $reader_one, $reader_two, $thesis);
            
            if($stmt->execute()){
                header('Location: success.php?name='.urlencode($name).'&email='.urlencode($email).'&date='.urlencode($date).'&timeslot='.urlencode($timeslot).'&room='.urlencode($room).'&first_reader='.urlencode($first_reader).'&second_reader='.urlencode($second_reader).'&thesis='.urlencode($thesis));
                exit;
            } else {
                // Handle error in booking
                $msg = "<div class='alert alert-danger'>Error in booking</div>";
            }
            $stmt->close();
        }
    } else {
        // Handle error in query execution
        $msg = "<div class='alert alert-danger'>Error in query execution</div>";
    }
    $mysqli->close();
}


?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Book Available Slot</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            position: relative;
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            width: 60%; 
            max-width: auto; 
            margin-left: auto;
            margin-right: auto;
        }
        .instruction-box {
            border: 1px solid #f39c12;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #fff5f5;
        }

        .room-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .room {
            background-color: #fff;
            text-align: center;
            margin: 10px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: calc(30% - 10px); 
        }

        .slot {
            margin: 5px 0;
        }

        .btn-success, .btn-danger {
            width: 100%;
            margin-top: 5px;
        }
        .divider {
            border-top: 1px solid #ccc;
            margin: 15px 0;
        }

    </style>
</head>
<body>
    <div class="container">
    <a onclick="history.back()" class="btn btn-primary">Go Back</a>

        <h3 class="text-center">Selected Date: <?php echo date('m/d/Y', strtotime($date)); ?></h3>
        <hr>
        <div class="instruction-box">
            Please choose an available slot for the room you prefer. Ensure your selected time aligns with your availability and preparation.
        </div>
        <div class="room-container">
            <?php foreach ($roomsAvailability as $roomName => $timeslots): ?>
                <div class="room">
                    <h3><?php echo htmlspecialchars($roomName); ?></h3>
                    <hr class="divider">
                    <?php foreach ($timeslots as $ts): ?>
                        <div class="slot">
                            <?php
                            if (in_array($ts, $bookings[$roomName] ?? [])) {
                                echo "<button class='btn btn-danger disabled' disabled> $ts (Unavailable)</button>";
                            } elseif (in_array($ts, $professorBookedSlots)) {
                                echo "<button class='btn btn-danger disabled' disabled> $ts (Unavailable)</button>";
                            } else {
                                echo "<button class='btn btn-success book' data-timeslot='$ts' data-room='".htmlspecialchars($roomName)."'> $ts (Available)</button>";
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>  
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

      <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="text-center"><strong>Confirmation</strong></h4>
                </div>

      <!-- Modal body -->
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="">Date</label>
                                    <input required type="text" name="date" class="form-control" value="<?php echo htmlspecialchars($_GET['date']); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Oral time</label>
                                    <input required type="text" readonly name="timeslot" id="timeslot" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Room Number</label>
                                    <input required type="text" readonly name="room" id="room" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Reader One</label>
                                    <input required type="text" name="reader_one" class="form-control" value="<?php echo htmlspecialchars($_GET['first_reader']); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Reader Two</label>
                                    <input required type="text" name="reader_two" class="form-control" value="<?php echo htmlspecialchars($_GET['second_reader']); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input required type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input required type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="">Thesis Topic</label>
                                    <textarea required name="thesis" class="form-control"><?php echo htmlspecialchars($thesis); ?></textarea>
                                </div>
                                <div class="form-group pull-right">
                                    <button class="btn btn-primary" type="submit" name="submit">Confirm Booking</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script>
        $(".book").click(function(){
            var timeslot = $(this).attr('data-timeslot');
            var room = $(this).attr('data-room');
            $("#slot").html(timeslot);
            $("#timeslot").val(timeslot);
            $("#room").val(room);
            $("input[name='name']").val("<?php echo addslashes($name); ?>");
            $("input[name='email']").val("<?php echo addslashes($email); ?>");
            $("textarea[name='thesis']").val("<?php echo addslashes($thesis); ?>");
            $("#myModal").modal("show");
        })
    </script>
</body>

</html>