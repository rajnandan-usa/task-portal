<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_admin = 0 LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && (password_verify($password, $user['password']) || md5($password) === $user['password'])) {
        $_SESSION['user_id'] = $user['id'];

        // Check password date and time
        $days = 999;
        if (!empty($user['last_password_change'])) {
            $lastChanged = new DateTime($user['last_password_change']);
            $now = new DateTime();
            $days = $now->diff($lastChanged)->days;
        }

        $_SESSION['force_password_change'] = ($days > 30);

        // Update last login
        $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")->execute([$user['id']]);

        if ($_SESSION['force_password_change']) {
            redirect('change_password.php');
        } else {
            redirect('dashboard.php');
        }
    } else {
        $error = 'Invalid email or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f4f8;
        }
        .login-card {
            max-width: 400px;
            margin: 5rem auto;
            padding: 2rem;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="login-card">
    <h4 class="mb-4 text-center text-primary">User Login</h4>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="user@example.com" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>
</div>
</body>
</html>
