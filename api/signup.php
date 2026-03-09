<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

session_start();
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data['username']);
$email = trim($data['email']);
$password = trim($data['password']);

if (!$username || !$email || !$password) {
  echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
  exit;
}

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// ✅ Mark user as admin if their username is "admin"
$isAdmin = ($username === 'admin') ? 1 : 0;

try {
  $stmt = $pdo->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
  $stmt->execute([$username, $email, $hashedPassword, $isAdmin]);

  echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
  echo json_encode(['status' => 'error', 'message' => 'Username or email already exists']);
}
