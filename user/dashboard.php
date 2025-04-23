<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

if (isset($_SESSION['force_password_change']) && $_SESSION['force_password_change']) {
    redirect('change_password.php');
}

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY start_time DESC");
$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f4f8;
        }
        .dashboard-card {
            margin-top: 3rem;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .table thead {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">User Panel</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="create_task.php">Create Task</a></li>
                <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container dashboard-card">
    <h4 class="mb-4 text-primary">My Submitted Tasks</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Start Time</th>
                    <th>Stop Time</th>
                    <th>Notes</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($tasks as $index => $task): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $task['start_time'] ?></td>
                    <td><?= $task['stop_time'] ?></td>
                    <td><?= htmlspecialchars($task['notes']) ?></td>
                    <td><?= htmlspecialchars($task['description']) ?></td>
                    <td>
                        <a href="edit_task.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($tasks) === 0): ?>
                <tr><td colspan="6" class="text-center text-muted">No tasks submitted yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
