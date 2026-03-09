<?php
require 'db.php';
$id = intval($_GET['id'] ?? 0);
$pdo->prepare("DELETE FROM replies WHERE question_id = ?")->execute([$id]);
$pdo->prepare("DELETE FROM questions WHERE id = ?")->execute([$id]);
echo json_encode(['message' => 'Question deleted']);
?>
