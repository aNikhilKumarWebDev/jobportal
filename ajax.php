<?php
session_start();
include 'includes/db.php';

// Login
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === 'admin@123') {
        $_SESSION['loggedin'] = true;
        echo 'success';
    } else {
        echo 'error';
    }
    exit;
}
