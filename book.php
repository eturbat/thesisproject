<?php


if(isset($_GET['availableSlots'])) {
    $availableSlots = explode(',', $_GET['availableSlots']);
    echo "<pre>Available Slots: ";
    print_r($availableSlots);
    echo "</pre>";
} else {
    $availableSlots = [];
}

$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

$thesis = isset($_GET['thesis']) ? $_GET['thesis'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';


if(isset($_GET['date'])){
    $date = $_GET['date'];
    $stmt = $mysqli->prepare("SELECT * FROM bookings WHERE date = ?");
    $stmt->bind_param('s', $date);
    $bookings = array();
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $bookings[] = $row['timeslot'];
            }
            $stmt->close();
        }
    }
}

if(isset($_POST['submit'])){
    $thesis = $_POST['thesis'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $timeslot = $_POST['timeslot'];
    $date = $_POST['date'];
    $room = $_POST['room'];
    $reader_one = $_POST['reader_one'];
    $reader_two = $_POST['reader_two'];
    
    $stmt = $mysqli->prepare("SELECT * FROM bookings WHERE date=? AND timeslot=?");
    $stmt->bind_param('ss', $date, $timeslot);
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $msg = "<div class='alert alert-danger'>Already Booked</div>";
        }else{
            $stmt = $mysqli->prepare("INSERT INTO bookings (name, email, date, timeslot, room, reader_one, reader_two) VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param('sssssss', $name, $email, $date, $timeslot, $room, $reader_one, $reader_two);

            // if($stmt->execute()){
            //     $stmt->close();
            //     $mysqli->close();
            //     header("Location: schedule.php?booking=success");
            //     exit;
            // }
            
            $stmt->execute();
            $msg = "<div class='alert alert-success'>Oral defense time successfully booked!</div>";
            $msg .= "<a href='schedule.php?&thesis=".$thesis."&name=".$name."&email=".$email."&first_reader=".$reader_one."&second_reader=".$reader_two."&room=".$room."' class='btn btn-info'>Go Back</a>";
            $bookings[] = $timeslot;
            $stmt->close();
            $mysqli->close();
        }
    }
}

?>


<!doctype html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/main.css">
  </head>

  <body>
  <div class="container">
        <h1 class="text-center">Please choose available slot on <?php echo date('m/d/Y', strtotime($date)); ?></h1><hr>
        <div>
            <?php echo isset($msg)?$msg:"";?>
        </div>
        <div class="row">
        <?php 
        foreach($availableSlots as $ts) {
            ?>
            <div class="col-md-2">
                <div class="form-group">
                    <?php if(in_array($ts, $bookings)) { ?>
                        <button class="btn btn-danger"> 
                            <?php echo $ts; ?>
                        </button>
                    <?php } else { ?>
                        <button class="btn btn-success book" data-timeslot="<?php echo $ts; ?>">
                            <?php echo $ts; ?>
                        </button>
                    <?php } ?>
                </div>
            </div>
            <?php 
        } 
        ?>
        </div>
    </div>
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

      <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title"> Confirmation <span id="slot"></span>
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
                                    <input required type="text" name="room" class="form-control" value="<?php echo htmlspecialchars($_GET['room']); ?>" readonly>
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
                                    <textarea required name="thesis" class="form-control" readonly><?php echo htmlspecialchars($thesis); ?></textarea>
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
            $("#slot").html(timeslot);
            $("#timeslot").val(timeslot);
            $("input[name='name']").val("<?php echo addslashes($name); ?>");
            $("input[name='email']").val("<?php echo addslashes($email); ?>");
            $("textarea[name='thesis']").val("<?php echo addslashes($thesis); ?>");
            $("#myModal").modal("show");
        })
    </script>
</body>

</html>