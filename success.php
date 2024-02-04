<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Success</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .success-message {
            text-align: center;
            margin-top: 50px;
        }
        .details {
            margin-top: 20px;
        }
        .detail-item {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="success-message">
        <h2>Booking Confirmed Successfully!</h2>
        <p>Your oral defense has been scheduled.</p>
        <a href="student_index.php" class="btn btn-primary">Go Back to Home</a>
    </div>
    <div class="details">
        <h3>Oral Defense Details:</h3>
        <p class="detail-item"><strong>Name:</strong> <?php echo htmlspecialchars($_GET['name']); ?></p>
        <p class="detail-item"><strong>Email:</strong> <?php echo htmlspecialchars($_GET['email']); ?></p>
        <p class="detail-item"><strong>Date:</strong> <?php echo htmlspecialchars($_GET['date']); ?></p>
        <p class="detail-item"><strong>Time Slot:</strong> <?php echo htmlspecialchars($_GET['timeslot']); ?></p>
        <p class="detail-item"><strong>Room:</strong> <?php echo htmlspecialchars($_GET['room']); ?></p>
        <p class="detail-item"><strong>First Reader:</strong> <?php echo htmlspecialchars($_GET['first_reader']); ?></p>
        <p class="detail-item"><strong>Second Reader:</strong> <?php echo htmlspecialchars($_GET['second_reader']); ?></p>
        <p class="detail-item"><strong>Thesis Topic:</strong> <?php echo htmlspecialchars($_GET['thesis']); ?></p>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
