<?php
require 'db.php';
$id = intval($_GET['id'] ?? 0);
$data = json_decode(file_get_contents('php://input'), true);
$stmt = $pdo->prepare("INSERT INTO replies (question_id, body, author) VALUES (?, ?, ?)");
$stmt->execute([$id, $data['body'], $data['author']]);
echo json_encode(['message' => 'Reply added']);
?>
