<?php
session_start();
require_once 'includes/functions.php';

// Redirect based on session
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    redirect('admin/dashboard.php');
} elseif (isset($_SESSION['user_id'])) {
    redirect('user/dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Welcome to Task Portal</h2>
    <div class="d-grid gap-3 col-6 mx-auto">
        <a href="admin/login.php" class="btn btn-dark btn-lg">Admin Login</a>
        <a href="user/login.php" class="btn btn-primary btn-lg">User Login</a>
    </div>
</div>
</body>
</html>
