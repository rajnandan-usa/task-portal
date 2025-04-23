<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['force_password_change']) {
    redirect('dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass !== $confirm_pass) {
        $error = "Passwords do not match.";
    } elseif (strlen($new_pass) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, last_password_change = NOW() WHERE id = ?");
        $stmt->execute([$hashed, $_SESSION['user_id']]);

        unset($_SESSION['force_password_change']);
        $success = "Password updated successfully.";
        redirect('dashboard.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Change Password</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"> <?= $error ?> </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"> <?= $success ?> </div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Change Password</button>
    </form>
</div>
</body>
</html>