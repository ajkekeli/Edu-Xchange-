<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode([]);
  exit;
}

$userId = $_SESSION['user_id'];

// Show all requests to all users
$stmt = $pdo->prepare("
  SELECT hr.*, u.username 
  FROM help_requests hr 
  JOIN users u ON hr.user_id = u.id 
  ORDER BY hr.created_at DESC
");
$stmt->execute();

$helpRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($helpRequests);
