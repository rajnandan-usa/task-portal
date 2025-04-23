<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="task_report.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['Start Time', 'Stop Time', 'Notes', 'Description']);

$stmt = $pdo->query("SELECT start_time, stop_time, notes, description FROM tasks ORDER BY start_time DESC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>