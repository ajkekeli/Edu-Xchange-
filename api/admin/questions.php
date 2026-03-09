<?php
require_once '../db.php';
session_start();

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
  http_response_code(403);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

$stmt = $pdo->query("SELECT id, title, body, author, date FROM questions ORDER BY date DESC");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
