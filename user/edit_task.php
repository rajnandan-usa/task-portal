<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

if (!isset($_GET['id'])) {
    redirect('dashboard.php');
}

$id = $_GET['id'];
$message = '';
$error = '';

// Fetch task details
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$task = $stmt->fetch();

if (!$task) {
    redirect('dashboard.php');
}

// Handle update form submission
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
        $update = $pdo->prepare("UPDATE tasks SET start_time = ?, stop_time = ?, notes = ?, description = ? WHERE id = ? AND user_id = ?");
        $update->execute([$start_time, $stop_time, $notes, $description, $id, $_SESSION['user_id']]);
        header("Location: edit_task.php?id=$id&success=1");
        exit;
    }
}

// Display success message
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = 'Task updated successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f4f8;
        }
        .edit-card {
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
                <li class="nav-item"><a class="nav-link" href="create_task.php">Create Task</a></li>
                <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="edit-card">
    <h4 class="mb-4 text-primary text-center">Edit Task</h4>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php elseif ($message): ?>
        <div class="alert alert-success text-center"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label for="start_time">Start Time</label>
            <input type="datetime-local" id="start_time" name="start_time" class="form-control"
                   value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($task['start_time']))) ?>" required>
        </div>
        <div class="mb-3">
            <label for="stop_time">Stop Time</label>
            <input type="datetime-local" id="stop_time" name="stop_time" class="form-control"
                   value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($task['stop_time']))) ?>" required>
        </div>
        <div class="mb-3">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" class="form-control" rows="2"><?= htmlspecialchars($task['notes']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="description">Description <span class="text-danger">*</span></label>
            <textarea id="description" name="description" class="form-control" rows="3" required><?= htmlspecialchars($task['description']) ?></textarea>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Update Task</button>
        </div>
    </form>
</div>
</body>
</html>
