<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <title>Welcome to the Oral Defense Scheduling System</title>
    <style>
        .logo {
            display: block;
            margin: auto;
            width: 100px; /* Adjust based on your logo's size */
            padding-top: 20px;
        }
        .access-pad {
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-top: 20px;
            text-align: center;
            padding: 40px 20px;
            border-radius: 10px;
        }
        .access-pad:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
        }
        .container {
            text-align: center;
        }
        h2 {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="img/logo.png" alt="Logo" class="logo"> <!-- Update src with your logo path -->
        <h2>Welcome to the Oral Defense Scheduling System</h2>
        <p>This system allows students to schedule their oral defense sessions and professors to indicate their availability.</p>
        
        <div class="row">
            <div class="col-sm-6">
                <div class="access-pad" onclick="window.location.href='student_index.php';">
                    <h3>Student Panel</h3>
                    <p>Click here to schedule your oral defense.</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="access-pad" onclick="window.location.href='professor_availability.php';">
                    <h3>Professor Panel</h3>
                    <p>Click here to indicate your availability.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</body>
</html>
