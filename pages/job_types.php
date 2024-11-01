<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.php');
    exit;
}

// Handle Job Type Actions
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Job Type
    if (isset($_POST['addJobType'])) {
        $job_type_name = $_POST['job_type_name'];
        $sql = "INSERT INTO JobTypes (job_type_name) VALUES ('$job_type_name')";
        if (executeQuery($conn, $sql)) {
            $message = "Job Type added successfully!";
            $message_type = "success";
        } else {
            $message = "Error adding Job Type.";
            $message_type = "danger";
        }
    }

    // Update Job Type
    if (isset($_POST['updateJobType'])) {
        $job_type_id = $_POST['job_type_id'];
        $job_type_name = $_POST['job_type_name'];
        $status = $_POST['status'];
        $sql = "UPDATE JobTypes SET job_type_name = '$job_type_name', status = '$status' WHERE job_type_id = $job_type_id";
        if (executeQuery($conn, $sql)) {
            $message = "Job Type updated successfully!";
            $message_type = "success";
        } else {
            $message = "Error updating Job Type.";
            $message_type = "danger";
        }
    }

    // Delete Job Type
    if (isset($_POST['deleteJobType'])) {
        $job_type_id = $_POST['job_type_id'];
        $sql = "DELETE FROM JobTypes WHERE job_type_id = $job_type_id";
        if (executeQuery($conn, $sql)) {
            $message = "Job Type deleted successfully!";
            $message_type = "success";
        } else {
            $message = "Error deleting Job Type.";
            $message_type = "danger";
        }
    }
}

// Load Job Types
$sql = "SELECT * FROM JobTypes";
$result = executeQuery($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Job Types</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }

        h2 {
            margin-top: 20px;
            text-align: center;
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

        .table {
            margin-top: 20px;
        }

        .form-inline {
            margin-bottom: 20px;
        }

        .btn-dashboard {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Manage Job Types</h2>

        <div class="text-center">
            <a href="../dashboard.php" class="btn btn-secondary btn-dashboard">Go to Dashboard</a>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                Add New Job Type
            </div>
            <div class="card-body">
                <form method="POST" action="" class="form-inline">
                    <input type="text" name="job_type_name" class="form-control mr-2" placeholder="New Job Type Name" required>
                    <button type="submit" name="addJobType" class="btn btn-primary">Add Job Type</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Existing Job Types
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Srl No</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $srlNo = 1;
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $srlNo++; ?></td>
                                <td><?php echo $row['job_type_name']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td>
                                    <button class="btn btn-info" onclick="toggleUpdateFields(<?php echo $row['job_type_id']; ?>)">Update</button>
                                    <form method="POST" action="" style="display:inline;">
                                        <input type="hidden" name="job_type_id" value="<?php echo $row['job_type_id']; ?>">
                                        <button type="submit" name="deleteJobType" class="btn btn-danger">Delete</button>
                                    </form>
                                    <div id="update-fields-<?php echo $row['job_type_id']; ?>" style="display:none; margin-top:10px;">
                                        <form method="POST" action="">
                                            <input type="hidden" name="job_type_id" value="<?php echo $row['job_type_id']; ?>">
                                            <input type="text" name="job_type_name" class="form-control mr-2" value="<?php echo $row['job_type_name']; ?>" required>
                                            <select name="status" class="form-control mr-2">
                                                <option value="active" <?php echo $row['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                                <option value="inactive" <?php echo $row['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                            </select>
                                            <button type="submit" name="updateJobType" class="btn btn-warning">Save</button>
                                            <button type="button" class="btn btn-secondary" onclick="toggleUpdateFields(<?php echo $row['job_type_id']; ?>)">Cancel</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function toggleUpdateFields(jobTypeId) {
            const fields = document.getElementById('update-fields-' + jobTypeId);
            if (fields.style.display === 'none' || fields.style.display === '') {
                fields.style.display = 'block';
            } else {
                fields.style.display = 'none';
            }
        }
    </script>
</body>

</html>
