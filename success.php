<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Success</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .success-message h2 {
            color: #28a745;
        }
        .details {
            margin-top: 10px;
            padding-top: 0px;
        }
        .details h3 {
            margin-bottom: 10px;  
        }
        .details p {
            text-align: left; 
            margin-bottom: 15px;   
        }
        .detail-item strong {
            color: #333;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            margin-top: 10px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .logo-img {
            width: 100px;
            margin: 10px auto;
        }
        .divider {
            border-top: 1px solid #ccc;
            margin: 15px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="success-message">
        <img src="img/logo.png" alt="Logo" class="logo-img">
        <h2><?php echo htmlspecialchars($_GET['name']); ?>, you all set!</h2>
        <p>Your oral defense has been scheduled. Best of luck on your upcoming defense!</p>
    </div>
    <hr class="divider">
    <div class="details">
        <h3><strong>Oral Defense Details</strong></h3>
        <p class="detail-item"><strong>Name:</strong> <?php echo htmlspecialchars($_GET['name']); ?></p>
        <p class="detail-item"><strong>Email:</strong> <?php echo htmlspecialchars($_GET['email']); ?></p>
        <p class="detail-item"><strong>Date:</strong> <?php echo htmlspecialchars($_GET['date']); ?></p>
        <p class="detail-item"><strong>Time Slot:</strong> <?php echo htmlspecialchars($_GET['timeslot']); ?></p>
        <p class="detail-item"><strong>Room:</strong> <?php echo htmlspecialchars($_GET['room']); ?></p>
        <p class="detail-item"><strong>First Reader:</strong> <?php echo htmlspecialchars($_GET['first_reader']); ?></p>
        <p class="detail-item"><strong>Second Reader:</strong> <?php echo htmlspecialchars($_GET['second_reader']); ?></p>
        <p class="detail-item"><strong>Thesis Topic:</strong> <?php echo htmlspecialchars($_GET['thesis']); ?></p>
    </div>
    <hr class="divider">
    <a href="index.php" class="btn btn-primary">Go Back to Home</a>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
