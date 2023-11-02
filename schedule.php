<?php

// if(isset($_GET['booking']) && $_GET['booking'] == 'success') {
//     echo "<div class='alert alert-success'>Oral defense time successfully booked!</div>";
// }

function getAvailabilityData($mysqli, $first_reader, $second_reader, $room, $month, $year) {
    $availabilityData = [
        'professors' => [],
        'rooms' => []
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

    // Fetch availability for room
    $stmt = $mysqli->prepare("SELECT date, timeslot FROM rooms WHERE name=? AND MONTH(date)=? AND YEAR(date)=?");
    $stmt->bind_param("sii", $room, $month, $year);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $availabilityData['rooms'][$row['date']][] = $row['timeslot'];
        }
    }
    $stmt->close();

    echo "<pre>Availability Data: ";
    print_r($availabilityData);
    echo "</pre>";

    return $availabilityData;
}

function findOverlappingSlots($availabilityData) {
    $overlappingSlots = [];

    foreach ($availabilityData['professors'] as $professor => $dates) {
        foreach ($dates as $date => $timeslots) {
            if (isset($availabilityData['rooms'][$date])) {
                $overlap = array_intersect($timeslots, $availabilityData['rooms'][$date]);
                if (!empty($overlap)) {
                    if (!isset($overlappingSlots[$date])) {
                        $overlappingSlots[$date] = $overlap;
                    } else {
                        $overlappingSlots[$date] = array_intersect($overlappingSlots[$date], $overlap);
                    }
                }
            }
        }
    }

    echo "<pre>Overlapping Slots: ";
    print_r($overlappingSlots);
    echo "</pre>";

    return $overlappingSlots;
}

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

$first_reader = isset($_GET['first_reader']) ? $_GET['first_reader'] : '';
$second_reader = isset($_GET['second_reader']) ? $_GET['second_reader'] : '';
$room = isset($_GET['room']) ? $_GET['room'] : '';

function build_calendar($month, $year, $first_reader, $second_reader, $room) {
    $mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
    $daysOfWeek = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
    $firstDayOfMonth = mktime(0,0,0,$month,1,$year);
    $numberDays = date('t',$firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];
    $datetoday = date('Y-m-d');
    
    $calendar = "<table class='table table-bordered'>";
    $calendar .= "<center><h1 class='heading'>Welcome to Oral Defense Scheduling Calendar</h1></center>";
    $calendar .= "<center><h2>$monthName $year</h2></center>";
    $calendar .= "<tr>";
    
    foreach($daysOfWeek as $day) {
        $calendar .= "<th class='header'>$day</th>";
    } 

    $currentDay = 1;
    $calendar .= "</tr><tr>";
    
    if ($dayOfWeek > 0) { 
        for($k = 0; $k < $dayOfWeek; $k++){
            $calendar .= "<td class='empty'></td>"; 
        }
    }
    
    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

    // Fetch and process availability data
    $availabilityData = getAvailabilityData($mysqli, $first_reader, $second_reader, $room, $month, $year);
    $overlappingSlots = findOverlappingSlots($availabilityData);

    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }
        
        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";
        $dayname = strtolower(date('l', strtotime($date)));
        $eventNum = 0;
        $today = $date == date('Y-m-d') ? "today" : "";
        
        if($date < date('Y-m-d')){
            $calendar .= "<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'>N/A</button>";
        }
        elseif($dayOfWeek == 0 || $dayOfWeek == 6) {
            $calendar .= "<td class='blocked'><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'>Weekend</button>";
        }
        else{
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
                            <a href='book.php?date=".$date."&first_reader=".$first_reader."&second_reader=".$second_reader."&room=".$room."&availableSlots=".$availableSlotsString."&thesis=".$thesis."&name=".$name."&email=".$email."' class='btn btn-success btn-xs'>
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
    }

    if ($dayOfWeek != 7) { 
        $remainingDays = 7 - $dayOfWeek;
        for($l = 0; $l < $remainingDays; $l++){
            $calendar .= "<td class='empty'></td>"; 
        }
    }
    
    $calendar .= "</tr>";
    $calendar .= "</table>";
    
    echo $calendar;
}


//setting oral defense month

$month = 4; 
$year = 2024; 

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
                    echo build_calendar($month, $year, $first_reader, $second_reader, $room);
                ?>
            </div>
        </div>
    </div>
</body>
</html>