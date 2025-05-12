<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name']);
    $last = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password'] ?? '');

    if (!$first || !$last || !$email || !$phone || !$password) {
        $error = 'All fields are required.';
    } else {
        try {
            // Check if email already exists
            $check = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $check->execute([$email]);

            if ($check->fetch()) {
                $error = 'Email already exists.';
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("
                    INSERT INTO users (first_name, last_name, email, phone, password, is_admin, last_login, last_password_change)
                    VALUES (?, ?, ?, ?, ?, 0, NOW(), NULL)
                ");
                $stmt->execute([$first, $last, $email, $phone, $hashed]);

                header('Location: users.php?success=1');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f4f8;
        }
        .form-card {
            max-width: 600px;
            margin: 3rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="create_user.php">Create User</a></li>
                <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
                <li class="nav-item"><a class="nav-link" href="task_list.php">Task List</a></li>
                <li class="nav-item"><a class="nav-link" href="download_report.php">Download Report</a></li>
                <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="form-card">
    <h4 class="mb-4 text-center text-primary">Create New User</h4>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control <?= empty($first) && $_SERVER['REQUEST_METHOD'] === 'POST' ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control <?= empty($last) && $_SERVER['REQUEST_METHOD'] === 'POST' ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control <?= $error === 'Email already exists.' ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            <?php if ($error === 'Email already exists.'): ?>
                <div class="text-danger mt-1">Email already exists.</div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control <?= empty($phone) && $_SERVER['REQUEST_METHOD'] === 'POST' ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="autoPasswordCheckbox" onchange="toggleAutoPassword()">
            <label class="form-check-label" for="autoPasswordCheckbox">Auto-generate password</label>
        </div>
        <div class="mb-3" id="passwordField">
            <label>Password</label>
            <div class="input-group">
                <input type="text" name="password" class="form-control <?= empty($password) && $_SERVER['REQUEST_METHOD'] === 'POST' ? 'is-invalid' : '' ?>" id="passwordInput">
                <button class="btn btn-outline-secondary" type="button" onclick="generatePassword()" id="generateBtn" style="display: none;">Generate</button>
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Create User</button>
        </div>
    </form>
</div>

<script>
    function toggleAutoPassword() {
        const checkbox = document.getElementById('autoPasswordCheckbox');
        const generateBtn = document.getElementById('generateBtn');
        const passwordInput = document.getElementById('passwordInput');

        if (checkbox.checked) {
            generateBtn.style.display = 'block';
            passwordInput.value = '';
        } else {
            generateBtn.style.display = 'none';
            passwordInput.value = '';
        }
    }

    function generatePassword() {
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$";
        let password = "";
        for (let i = 0; i < 10; i++) {
            password += charset[Math.floor(Math.random() * charset.length)];
        }
        document.getElementById('passwordInput').value = password;
    }
    window.onload = toggleAutoPassword;
</script>
</body>
</html>
