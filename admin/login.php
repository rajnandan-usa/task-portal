<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_admin = 1 LIMIT 1");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && (password_verify($password, $admin['password']) || md5($password) === $admin['password'])) {
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['is_admin'] = true;
        redirect('dashboard.php');
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .login-card {
            max-width: 400px;
            margin: 5rem auto;
            padding: 2rem;
            border-radius: 12px;
            background-color: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<div class="login-card">
    <h4 class="mb-4 text-center text-primary">Admin Login</h4>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="admin@example.com" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </div>
    </form>
</div>
</body>
</html>
