<?php
session_start();
if (!isset($_SESSION['username'])) {
  http_response_code(403);
  echo "Unauthorized";
  exit;
}

require_once 'db.php';

$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$username = $_SESSION['username'] ?? 'Anonymous';

if (empty($title) || !isset($_FILES['resource_file'])) {
  http_response_code(400);
  echo "Missing fields.";
  exit;
}

$targetDir = '../uploads/';
if (!is_dir($targetDir)) {
  mkdir($targetDir, 0755, true);
}

$filename = time() . '_' . basename($_FILES['resource_file']['name']);
$targetFile = $targetDir . $filename;

if (move_uploaded_file($_FILES['resource_file']['tmp_name'], $targetFile)) {
  $filePath = 'uploads/' . $filename;

  $stmt = $pdo->prepare("INSERT INTO resources (title, description, file_path, uploaded_by) VALUES (?, ?, ?, ?)");
  $stmt->execute([$title, $description, $filePath, $username]);

  echo "File uploaded successfully.";
} else {
  http_response_code(500);
  echo "Error uploading file.";
}
