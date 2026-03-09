<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['username'])) {
    echo json_encode([
        'logged_in' => true,
        'username' => $_SESSION['username'],
        'is_admin' => $_SESSION['is_admin'] ?? 0
    ]);
} else {
    echo json_encode(['logged_in' => false]);
}
