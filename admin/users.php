<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
$successMsg = '';
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $successMsg = 'User created successfully!';
}


$stmt = $pdo->query("SELECT id, first_name, last_name, email, phone, last_login, last_password_change FROM users WHERE is_admin = 0");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Users</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f4f8;
        }
        .user-card {
            margin-top: 3rem;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .table thead th {
            vertical-align: middle;
            background-color: #e9ecef;
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
                <li class="nav-item"><a class="nav-link active" href="users.php">Users</a></li>
                <li class="nav-item"><a class="nav-link" href="task_list.php">Task List</a></li>
                <li class="nav-item"><a class="nav-link" href="download_report.php">Download Report</a></li>
                <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container user-card">
<?php if (!empty($successMsg)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $successMsg ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

    <h4 class="mb-4 text-primary">Registered Users</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Last Login</th>
                    <th>Last Password Change</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $index => $user): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($user['first_name']) ?></td>
                        <td><?= htmlspecialchars($user['last_name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <td><?= $user['last_login'] ?? '—' ?></td>
                        <td><?= $user['last_password_change'] ?? '—' ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($users) === 0): ?>
                    <tr><td colspan="7" class="text-center text-muted">No users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
