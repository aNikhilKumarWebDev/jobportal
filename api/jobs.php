<?php
// jobs.php
include '../includes/db.php';
include '../includes/functions.php';

// Set headers for JSON response
header('Content-Type: application/json');

$response = [];

// Set default filters (if not provided)
$searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;
$jobTypeId = isset($_GET['job_type_id']) ? intval($_GET['job_type_id']) : null;

// Construct job query with filters
$sql = "SELECT j.job_id, j.job_title, j.job_description, j.job_image, j.last_date_to_apply, 
               c.category_name, jt.job_type_name
        FROM Jobs j
        JOIN Categories c ON j.category_id = c.category_id
        JOIN JobTypes jt ON j.job_type_id = jt.job_type_id
        WHERE j.status = 'active'";

// Apply optional filters
if ($categoryId) {
    $sql .= " AND j.category_id = $categoryId";
}
if ($jobTypeId) {
    $sql .= " AND j.job_type_id = $jobTypeId";
}
if ($searchTerm) {
    $sql .= " AND (j.job_title LIKE '%$searchTerm%' OR j.job_description LIKE '%$searchTerm%')";
}

// Execute the job query
$result = executeQuery($conn, $sql);
while ($job = $result->fetch_assoc()) {
    $response[] = $job;
}

// Send the response as JSON
echo json_encode($response);
