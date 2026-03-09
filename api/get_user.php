<?php
session_start();

if (isset($_SESSION['username'])) {
    echo json_encode([
        'username' => $_SESSION['username'],
        'is_admin' => $_SESSION['is_admin'] ?? 0
    ]);
} else {
    echo json_encode(['username' => null]);
}
