<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['username'])) {
    $users = json_decode(file_get_contents('users.json'), true);
    $username = $_SESSION['username'];
    $preferences = isset($users[$username]['preferences']) ? $users[$username]['preferences'] : [];

    echo json_encode([
        'username' => $username,
        'preferences' => $preferences
    ]);
} else {
    echo json_encode(['error' => 'User not logged in']);
}
?>
