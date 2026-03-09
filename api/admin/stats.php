<?php
require_once '../db.php';
session_start();

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
  http_response_code(403);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

echo json_encode([
  'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
  'questions' => $pdo->query("SELECT COUNT(*) FROM questions")->fetchColumn(),
  'resources' => $pdo->query("SELECT COUNT(*) FROM resources")->fetchColumn(),
  'help_requests' => $pdo->query("SELECT COUNT(*) FROM help_requests")->fetchColumn()
]);
