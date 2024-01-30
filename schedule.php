<?php

$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');

// Check for database connection error
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch the date range from the database
$query = "SELECT start_date, end_date FROM defense_schedule ORDER BY id DESC LIMIT 1";
$result = $mysqli->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $start_date = new DateTime($row['start_date']);
    $end_date = new DateTime($row['end_date']);
} else {
    // Handle case where no dates are set
    echo "No defense schedule dates are set.";
    exit;
}

// Convert the start and end dates to month and year for the calendar
$month = $start_date->format('m');
$year = $start_date->format('Y');

// if(isset($_GET['booking']) && $_GET['booking'] == 'success') {
//     echo "<div class='alert alert-success'>Oral defense time successfully booked!</div>";
// }

function getAvailabilityData($mysqli, $first_reader, $second_reader, $month, $year) {
    $availabilityData = [
        'professors' => []
    ];

    // Fetch availability for first reader
    $stmt = $mysqli->prepare("SELECT date, timeslot FROM professors WHERE name=? AND MONTH(date)=? AND YEAR(date)=?");
    $stmt->bind_param("sii", $first_reader, $month, $year);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $availabilityData['professors'][$first_reader][$row['date']][] = $row['timeslot'];
        }
    }
    $stmt->close();

    // Fetch availability for second reader

    $stmt = $mysqli->prepare("SELECT date, timeslot FROM professors WHERE name=? AND MONTH(date)=? AND YEAR(date)=?");
    $stmt->bind_param("sii", $second_reader, $month, $year);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $availabilityData['professors'][$second_reader][$row['date']][] = $row['timeslot'];
        }
    }
    $stmt->close();

    // // Fetch availability for room
    // $stmt = $mysqli->prepare("SELECT date, timeslot FROM rooms WHERE name=? AND MONTH(date)=? AND YEAR(date)=?");
    // $stmt->bind_param("sii", $room, $month, $year);
    // if ($stmt->execute()) {
    //     $result = $stmt->get_result();
    //     while ($row = $result->fetch_assoc()) {
    //         $availabilityData['rooms'][$row['date']][] = $row['timeslot'];
    //     }
    // }
    // $stmt->close();

    echo "<pre>Availability Data: ";
    print_r($availabilityData);
    echo "</pre>";

    return $availabilityData;
}


function findOverlappingSlots($availabilityData, $first_reader, $second_reader) {
    $overlappingSlots = [];

    // Only compare availability between first and second readers
    if (isset($availabilityData['professors'][$first_reader]) && isset($availabilityData['professors'][$second_reader])) {
        foreach ($availabilityData['professors'][$first_reader] as $date => $firstReaderSlots) {
            if (isset($availabilityData['professors'][$second_reader][$date])) {
                $secondReaderSlots = $availabilityData['professors'][$second_reader][$date];
                $overlap = array_intersect($firstReaderSlots, $secondReaderSlots);
                if (!empty($overlap)) {
                    $overlappingSlots[$date] = $overlap;
                }
            }
        }
    }

    echo "<pre>Overlapping Slots: ";
    print_r($overlappingSlots);
    echo "</pre>";

    return $overlappingSlots;
}

$first_reader = isset($_GET['first_reader']) ? $_GET['first_reader'] : '';
$second_reader = isset($_GET['second_reader']) ? $_GET['second_reader'] : '';
$room = isset($_GET['room']) ? $_GET['room'] : '';


function checkSlots($mysqli, $date){
    $stmt = $mysqli->prepare("SELECT * FROM bookings WHERE date=?");
    $stmt->bind_param('s', $date);
    $totalbookings = 0;
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $totalbookings++;
            }
        }
        $stmt->close();
    }
    return $totalbookings;
}

function build_calendar($start_date, $end_date, $first_reader, $second_reader, $room) {
    $mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
    $daysOfWeek = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
    
    $startDateTime = new DateTime($start_date);
    $endDateTime = new DateTime($end_date);

    $month = $startDateTime->format('m');
    $year = $startDateTime->format('Y');

    $firstDayOfMonth = mktime(0,0,0,$month,1,$year);
    $numberDays = date('t',$firstDayOfMonth);

    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];
    
    $calendar = "<table class='table table-bordered'>";
    $calendar .= "<center><h1 class='heading'>Welcome to Oral Defense Scheduling Calendar</h1></center>";
    $calendar .= "<center><h2>$monthName $year</h2></center>";
    $calendar .= "<tr>";
    
    foreach($daysOfWeek as $day) {
        $calendar .= "<th class='header'>$day</th>";
    } 

    $calendar .= "</tr><tr>";
    
    if ($dayOfWeek > 0) { 
        for($k = 0; $k < $dayOfWeek; $k++){
            $calendar .= "<td class='empty'></td>"; 
        }
    }
    
    $currentDay = 1;
    // Fetch and process availability data
    $availabilityData = getAvailabilityData($mysqli, $first_reader, $second_reader, $month, $year);
    $overlappingSlots = findOverlappingSlots($availabilityData, $first_reader, $second_reader);

    while ($currentDay <= $numberDays) {
        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";

        if (new DateTime($date) < $startDateTime || new DateTime($date) > $endDateTime) {
            if ($dayOfWeek == 7) {
                $dayOfWeek = 0;
                $calendar .= "</tr><tr>";
            }
            $calendar .= "<td class='empty'></td>";
            $currentDay++;
            $dayOfWeek++;
            continue;
        }
        
        $dayname = strtolower(date('l', strtotime($date)));
        $eventNum = 0;
        $today = $date == date('Y-m-d') ? "today" : "";
        
        if($date < date('Y-m-d')){
            $calendar .= "<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'>N/A</button>";
        }
        elseif($dayOfWeek == 0 || $dayOfWeek == 6) {
            $calendar .= "<td class='blocked'><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'>Weekend</button>";
        }
        else {
            if (isset($overlappingSlots[$date]) && count($overlappingSlots[$date]) > 0) {
                $totalbookings = checkSlots($mysqli, $date);
                $availableslots = count($overlappingSlots[$date]) - $totalbookings;
                $availableSlotsString = implode(',', $overlappingSlots[$date]);

                $thesis = isset($_GET['thesis']) ? $_GET['thesis'] : '';
                $name = isset($_GET['name']) ? $_GET['name'] : '';
                $email = isset($_GET['email']) ? $_GET['email'] : '';

                if ($availableslots > 0) {
                    $calendar .= "
                    <td class='$today'>
                        <h4>$currentDay</h4> 
                            <a href='book.php?date=".$date."&first_reader=".$first_reader."&second_reader=".$second_reader."&availableSlots=".$availableSlotsString."&thesis=".$thesis."&name=".$name."&email=".$email."' class='btn btn-success btn-xs'>
                                Book
                            </a>
                    <small><i>$availableslots slots left</i></small>";
                } else {
                    $calendar .= "<td class='$today'><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'>All Booked</button>";
                }
            } else {
                $calendar .= "<td class='$today'><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'>Not Available</button>";
            }
        }
        
        $calendar .= "</td>";
        
        $currentDay++;
        $dayOfWeek++;

        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }
    }

    while ($dayOfWeek > 0 && $dayOfWeek < 7) {
        $calendar .= "<td class='empty'></td>";
        $dayOfWeek++;
    }
    
    $calendar .= "</tr></table>";
    return $calendar;
}

?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <style>
        @media only screen and (max-width: 760px),
        (min-device-width: 802px) and (max-device-width: 1020px) {

        }

        @media (min-width: 641px) {

        }

        .row{
            margin-top: 20px;
        }
        
        .today{
            background: yellow;
        }

        .blocked {
            background: #ccc;
        }

        .heading {
            background: #F8C42C;
            padding: 30px;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                    echo build_calendar($start_date->format('Y-m-d'), $end_date->format('Y-m-d'), $first_reader, $second_reader, $room);
                ?>
            </div>
        </div>
    </div>
</body>
</html>