<?php
require_once '../db.php';
session_start();

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
  http_response_code(403);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

$stmt = $pdo->query("SELECT id, topic, description, username, created_at, resolved FROM help_requests ORDER BY created_at DESC");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
