<?php 
  $timeslots = ["09:00-09:50", "10:00-10:50", "11:00-11:50", "12:00-12:50", "13:00-13:50", "14:00-14:50", "15:00-15:50", "16:00-16:50"]; 
?>

<form action="submit_room_availability.php" method="post">
  <label for="room">Room:</label>
  <select name="room" id="room">
    <option value="room200">Room 200</option>
    <option value="room205">Room 205</option>

  </select>
  
  <?php
  for ($i = 1; $i <= 30; $i++) {
    $date = sprintf("2024-04-%02d", $i);
    echo "<h4>$date</h4>";
    foreach ($timeslots as $timeslot) {
      $inputName = "availability[$date][$timeslot]";
      echo "<input type='checkbox' name='$inputName'> $timeslot ";
    }
    echo "<br>";
  }
  ?>

    <input type="submit" value="Submit">
</form>