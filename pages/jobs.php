<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.php');
    exit;
}

// Initialize variables for feedback messages
$message = '';
$message_type = '';

// Process form actions based on request method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_action = $_POST['form_action'] ?? '';
    $job_image = null;

    // Handle image upload
    if (isset($_FILES['job_image']) && $_FILES['job_image']['error'] == 0) {
        $target_dir = "../uploads/";
        $job_image = basename($_FILES['job_image']['name']);
        $target_file = $target_dir . $job_image;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_file_types = ['jpg', 'jpeg', 'png', 'gif'];

        // Validate file is an image, has a correct size and type
        $check = getimagesize($_FILES['job_image']['tmp_name']);
        if ($check && $_FILES['job_image']['size'] <= 2000000 && in_array($imageFileType, $allowed_file_types)) {
            if (!move_uploaded_file($_FILES['job_image']['tmp_name'], $target_file)) {
                $message = "Error uploading file.";
                $message_type = "danger";
                $job_image = null;
            }
        } else {
            $message = "Invalid image file or size exceeded (2MB limit).";
            $message_type = "danger";
            $job_image = null;
        }
    }

    // Sanitize inputs
    $job_title = $conn->real_escape_string($_POST['job_title']);
    $job_description = $conn->real_escape_string($_POST['job_description']);
    $job_type_id = intval($_POST['job_type_id']);
    $category_id = intval($_POST['category_id']);
    $last_date_to_apply = $conn->real_escape_string($_POST['last_date_to_apply']);

    // Handle CRUD operations based on form action
    if ($form_action === 'addJob') {
        $sql = "INSERT INTO Jobs (job_title, job_description, job_type_id, category_id, last_date_to_apply, job_image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisss", $job_title, $job_description, $job_type_id, $category_id, $last_date_to_apply, $job_image);
        if ($stmt->execute()) {
            $message = "Job added successfully!";
            $message_type = "success";
        } else {
            $message = "Error adding job.";
            $message_type = "danger";
        }
    } elseif ($form_action === 'updateJob') {
        $job_id = intval($_POST['job_id']);
        $sql = "UPDATE Jobs SET job_title=?, job_description=?, job_type_id=?, category_id=?, last_date_to_apply=?";
        $sql .= $job_image ? ", job_image=?" : "";
        $sql .= " WHERE job_id=?";

        $stmt = $conn->prepare($sql);
        $job_image ? $stmt->bind_param("ssisssi", $job_title, $job_description, $job_type_id, $category_id, $last_date_to_apply, $job_image, $job_id)
            : $stmt->bind_param("ssissi", $job_title, $job_description, $job_type_id, $category_id, $last_date_to_apply, $job_id);

        if ($stmt->execute()) {
            $message = "Job updated successfully!";
            $message_type = "success";
        } else {
            $message = "Error updating job.";
            $message_type = "danger";
        }
    } elseif (isset($_POST['deleteJob'])) {
        $job_id = intval($_POST['job_id']);
        $stmt = $conn->prepare("DELETE FROM Jobs WHERE job_id = ?");
        $stmt->bind_param("i", $job_id);
        if ($stmt->execute()) {
            $message = "Job deleted successfully!";
            $message_type = "success";
        } else {
            $message = "Error deleting job.";
            $message_type = "danger";
        }
    }
}

// Load jobs data
$sql = "SELECT j.*, c.category_name, jt.job_type_name FROM Jobs j JOIN Categories c ON j.category_id = c.category_id JOIN JobTypes jt ON j.job_type_id = jt.job_type_id";
$result = $conn->query($sql);

// Fetch categories and job types for dropdowns
$category_options = $conn->query("SELECT category_id, category_name FROM Categories");
$job_type_options = $conn->query("SELECT job_type_id, job_type_name FROM JobTypes");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs</title>
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
    </style>
</head>

<body>
    <div class="container">
        <h2>Manage Jobs</h2>
        <div class="text-center">
            <a href="../dashboard.php" class="btn btn-secondary">Go to Dashboard</a>
        </div>
        <?php if ($message): ?>
            <div class="alert alert-<?= $message_type; ?> alert-dismissible fade show" role="alert">
                <?= $message; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>
        <div class="card">
            <div class="card-header">Add New Job</div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="job_id" id="job_id">
                    <input type="hidden" name="form_action" id="form_action" value="addJob">

                    <div class="form-group">
                        <label for="job_title">Job Title</label>
                        <input type="text" name="job_title" id="job_title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="job_description">Job Description</label>
                        <textarea name="job_description" id="job_description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="job_type_id">Job Type</label>
                        <select name="job_type_id" id="job_type_id" class="form-control" required>
                            <?php while ($type = $job_type_options->fetch_assoc()) echo "<option value='{$type['job_type_id']}'>{$type['job_type_name']}</option>"; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <?php while ($category = $category_options->fetch_assoc()) echo "<option value='{$category['category_id']}'>{$category['category_name']}</option>"; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="job_image">Job Image (optional)</label>
                        <input type="file" name="job_image" id="job_image" class="form-control-file">
                    </div>
                    <div class="form-group">
                        <label for="last_date_to_apply">Last Date to Apply</label>
                        <input type="date" name="last_date_to_apply" id="last_date_to_apply" class="form-control" min="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <button type="submit" id="action_button" class="btn btn-primary">Add Job</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Existing Jobs</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $srlNo = 1;
                        while ($job = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $srlNo++; ?></td>
                                <td><?= htmlspecialchars($job['job_title']); ?></td>
                                <td><?= htmlspecialchars($job['job_description']); ?></td>
                                <td><?= htmlspecialchars($job['category_name']); ?></td>
                                <td><?= htmlspecialchars($job['job_type_name']); ?></td>
                                <td><img src="../uploads/<?= htmlspecialchars($job['job_image']); ?>" alt="Job Image" width="50" height="50"></td>
                                <td>
                                    <button class="btn btn-sm btn-info editJob" data-job='<?= json_encode($job); ?>'>Edit</button>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="form_action" value="deleteJob">
                                        <input type="hidden" name="job_id" value="<?= $job['job_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".editJob").click(function() {
                const job = $(this).data("job");
                $("#job_id").val(job.job_id);
                $("#job_title").val(job.job_title);
                $("#job_description").val(job.job_description);
                $("#job_type_id").val(job.job_type_id);
                $("#category_id").val(job.category_id);
                $("#last_date_to_apply").val(formatDateToYMD(job.last_date_to_apply));
                $("#form_action").val("updateJob");
                $("#action_button").text("Update Job");
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
        // Convert to Y-m-d format
        function formatDateToYMD(dateTimeString) {
            const date = new Date(dateTimeString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
    </script>
</body>

</html>