<?php
require 'db.php';
$search = $_GET['search'] ?? '';
if ($search) {
  $stmt = $pdo->prepare("SELECT * FROM questions WHERE title LIKE ? OR body LIKE ? ORDER BY date DESC");
  $stmt->execute(["%$search%", "%$search%"]);
} else {
  $stmt = $pdo->query("SELECT * FROM questions ORDER BY date DESC");
}
echo json_encode($stmt->fetchAll());
?>
