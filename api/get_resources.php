<?php
require_once 'db.php';

$stmt = $pdo->query("SELECT id, title, description, file_path FROM resources ORDER BY id DESC");
$resources = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($resources);
