<?php
require 'db.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM replies WHERE question_id = ? ORDER BY date ASC");
$stmt->execute([$id]);
echo json_encode($stmt->fetchAll());
?>
