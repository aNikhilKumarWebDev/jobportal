<?php
session_start();

// Static credentials
$static_username = 'admin';
$static_password = 'admin@123';

// Initialize a message variable to store feedback
$message = "";

// Login processing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate credentials
    if ($username === $static_username && $password === $static_password) {
        $_SESSION['loggedin'] = true;
        header("Location: dashboard.php"); // Redirect to dashboard on successful login
        exit;
    } else {
        $message = "Invalid credentials"; // Feedback for failed login
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
    <style>
        *,
        *:before,
        *:after {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: 'Raleway', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
            /* Light background for the body */
        }

        .container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .top,
        .bottom {
            position: absolute;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            overflow: visible;
            /* Allow overflow to be visible */
        }

        .top:before,
        .top:after,
        .bottom:before,
        .bottom:after {
            content: '';
            display: block;
            position: absolute;
            width: 200vmax;
            height: 200vmax;
            top: 50%;
            left: 50%;
            margin-top: -100vmax;
            transform-origin: 0 50%;
            transition: all 0.5s cubic-bezier(0.445, 0.05, 0, 1);
            z-index: 10;
            opacity: 0.65;
        }

        .top:before {
            transform: rotate(45deg);
            background: #e46569;
        }

        .top:after {
            transform: rotate(135deg);
            background: #ecaf81;
        }

        .bottom:before {
            transform: rotate(-45deg);
            background: #60b8d4;
        }

        .bottom:after {
            transform: rotate(-135deg);
            background: #3745b5;
        }

        .center {
            position: relative;
            /* Change to relative */
            width: 400px;
            height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            opacity: 0;
            /* Start hidden */
            transition: all 0.5s cubic-bezier(0.445, 0.05, 0, 1);
            color: #333;
            z-index: 20;
            /* Ensure it's above the background */
        }

        .center input {
            width: 100%;
            padding: 15px;
            margin: 5px;
            border-radius: 5px;
            /* Slightly rounded corners */
            border: 1px solid #ccc;
            font-family: inherit;
        }

        .container:hover .center {
            opacity: 1;
            /* Show the center section on hover */
            transition-delay: 0.2s;
            /* Delay the appearance */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="top"></div>
        <div class="bottom"></div>
        <div class="center">
            <h2>Please Sign In</h2>
            <?php if ($message): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $message; ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <input type="text" name="username" placeholder="User Name" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>