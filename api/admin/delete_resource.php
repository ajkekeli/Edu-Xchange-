<?php
require_once '../db.php';
session_start();

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
  http_response_code(403);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing resource ID']);
  exit;
}


$stmt = $pdo->prepare("SELECT file_path FROM resources WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetchColumn();
if ($file && file_exists("../../" . $file)) {
  unlink("../../" . $file);
}

$stmt = $pdo->prepare("DELETE FROM resources WHERE id = ?");
$stmt->execute([$id]);

echo json_encode(['success' => true]);
