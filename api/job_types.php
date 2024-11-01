<?php
// job_types.php
include '../includes/db.php';
include '../includes/functions.php';

// Set headers for JSON response
header('Content-Type: application/json');

$response = [];

// Fetch only active job types
$sql = "SELECT job_type_id, job_type_name FROM JobTypes WHERE status = 'active'";
$result = executeQuery($conn, $sql);
while ($job_type = $result->fetch_assoc()) {
    $response[] = $job_type;
}

// Send the response as JSON
echo json_encode($response);
?>
