<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <title>Job Portal</title>
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }

        h1 {
            margin-top: 20px;
            text-align: center;
            font-family: 'Arial', sans-serif;
            color: #007bff;
        }

        .container {
            margin-top: 30px;
        }

        .card {
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
        }

        .card-body {
            background-color: white;
        }

        .nav-link {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Job Portal Dashboard</h1>
        <div class="card">
            <div class="card-header">
                Welcome to the Job Portal
            </div>
            <div class="card-body">
                <h5 class="card-title">Manage Your Job Listings</h5>
                <p class="card-text">Use the navigation links below to access different sections of the dashboard.</p>

                <nav>
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link" href="pages/categories.php">Categories</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/job_types.php">Job Types</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/jobs.php">Jobs</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>