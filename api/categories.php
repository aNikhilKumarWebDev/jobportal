<?php
// categories.php
include '../includes/db.php';
include '../includes/functions.php';

// Set headers for JSON response
header('Content-Type: application/json');

$response = [];

// Fetch only active categories
$sql = "SELECT category_id, category_name FROM Categories WHERE status = 'active'";
$result = executeQuery($conn, $sql);
while ($category = $result->fetch_assoc()) {
    $response[] = $category;
}

// Send the response as JSON
echo json_encode($response);
