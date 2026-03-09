<?php
session_start();
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data['username']);
$password = trim($data['password']);

if (!$username || !$password) {
  echo json_encode(['status' => 'error', 'message' => 'Username and password required']);
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['username'] = $user['username'];
  $_SESSION['is_admin'] = $user['is_admin']; // ✅ Set is_admin in session

  echo json_encode([
    'status' => 'success',
    'is_admin' => $user['is_admin'] // ✅ send to frontend (optional)
  ]);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
}
