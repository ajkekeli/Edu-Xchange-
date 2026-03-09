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
  echo json_encode(['error' => 'User ID missing']);
  exit;
}

$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);
echo json_encode(['success' => true]);
