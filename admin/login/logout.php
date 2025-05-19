<?php
// logout.php - Ends the user session
session_start();

// Remove PHP session
session_unset();
session_destroy();

// Remove application session token
if (isset($_COOKIE['session_token'])) {
    $token = $_COOKIE['session_token'];
    // Delete from DB
    $stmt = $pdo->prepare("DELETE FROM sessions WHERE token = ?");
    $stmt->execute([$token]);
    // Expire cookie
    setcookie('session_token', '', time() - 3600, '/');
}

// setcookie('session_token', $token, [
//     'expires' => $remember ? time()+60*60*24*30 : 0,
//     'path' => '/',
//     'secure' => true,      // set to true when using HTTPS
//     'httponly' => true,
//     'samesite' => 'Lax'
// ]);

header('Location: http://localhost/h26/index.php');; exit;
?>