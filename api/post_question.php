<?php
require 'db.php';
$data = json_decode(file_get_contents('php://input'), true);
$stmt = $pdo->prepare("INSERT INTO questions (title, body, author) VALUES (?, ?, ?)");
$stmt->execute([$data['title'], $data['body'], $data['author']]);
echo json_encode(['message' => 'Question added']);
?>
