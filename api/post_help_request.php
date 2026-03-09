<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
  http_response_code(403);
  echo "Not authorized";
  exit;
}

$userId = $_SESSION['user_id'];
$subject = $_POST['subject'] ?? '';
$description = $_POST['description'] ?? '';
$contact_email = $_POST['contact_email'] ?? '';

if (!$subject || !$description || !$contact_email) {
  echo "Missing fields.";
  exit;
}

$stmt = $pdo->prepare("
  INSERT INTO help_requests (user_id, subject, description, contact_email) 
  VALUES (?, ?, ?, ?)
");
$stmt->execute([$userId, $subject, $description, $contact_email]);

echo "Help request submitted!";
