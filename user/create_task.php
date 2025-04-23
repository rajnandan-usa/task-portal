<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_time = trim($_POST['start_time'] ?? '');
    $stop_time = trim($_POST['stop_time'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($start_time) || empty($stop_time) || empty($description)) {
        $error = 'Start time, stop time, and description are required.';
    } elseif (strtotime($stop_time) <= strtotime($start_time)) {
        $error = 'Stop time must be later than start time.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, start_time, stop_time, notes, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['user_id'],
            $start_time,
            $stop_time,
            $notes,
            $description
        ]);
        header('Location: dashboard.php?success=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Task</title>
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
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">User Panel</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="create_task.php">Create Task</a></li>
                <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="form-card">
    <h4 class="mb-4 text-primary text-center">Submit a New Task</h4>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php elseif ($message): ?>
        <div class="alert alert-success text-center"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label>Start Time</label>
            <input type="datetime-local" name="start_time" class="form-control" required value="<?= htmlspecialchars($_POST['start_time'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label>Stop Time</label>
            <input type="datetime-local" name="stop_time" class="form-control" required value="<?= htmlspecialchars($_POST['stop_time'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control" rows="2"><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label>Description <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Submit Task</button>
        </div>
    </form>
</div>
</body>
</html>
