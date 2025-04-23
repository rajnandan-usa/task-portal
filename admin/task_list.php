<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

$stmt = $pdo->query("
    SELECT t.id, t.user_id, u.first_name, u.last_name, t.start_time, t.stop_time, t.notes, t.description
    FROM tasks t
    JOIN users u ON t.user_id = u.id
    ORDER BY t.start_time DESC
");
$tasks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f4f8;
        }
        .task-card {
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
                <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
                <li class="nav-item"><a class="nav-link active" href="task_list.php">Task List</a></li>
                <li class="nav-item"><a class="nav-link" href="download_report.php">Download Report</a></li>
                <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container task-card">
    <h4 class="mb-4 text-primary">All Submitted Tasks</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Start Time</th>
                    <th>Stop Time</th>
                    <th>Notes</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $index => $task): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($task['first_name'] . ' ' . $task['last_name']) ?></td>
                        <td><?= $task['start_time'] ?></td>
                        <td><?= $task['stop_time'] ?></td>
                        <td><?= htmlspecialchars($task['notes']) ?></td>
                        <td><?= htmlspecialchars($task['description']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($tasks) === 0): ?>
                    <tr><td colspan="6" class="text-center text-muted">No tasks found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
