<?php

// login.php - Processes login and creates session (template unchanged)
session_start();

// Database config
$host = 'localhost';
$db   = 'mithai_shop';
$user = 'BhushanN';
$pass = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new PDO($dsn, $user, $pass, $options);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $passw = trim($_POST['pwd'] ?? '');
    $remember = isset($_POST['remember']) ? 1 : 0;
    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
    if (!$passw) $errors[] = 'Password required.';

    if ($errors) {
        header('Location: login.html?error=' . urlencode(implode(' ', $errors)));
        exit;
    }

    // Fetch user
    $stmt = $pdo->prepare("SELECT id, first_name, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    $success = 0;

    if ($user && password_verify($passw, $user['password'])) {
        $success = 1;
        // Create application session token
        $token = bin2hex(random_bytes(16));
        $expires = $remember ? date('Y-m-d H:i:s', strtotime('+30 days')) : null;
        $created = date('Y-m-d H:i:s');

        // Store in sessions table
        $stmt = $pdo->prepare("INSERT INTO sessions (user_id, token, created_at, expires_at) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user['id'], $token, $created, $expires]);

        // Set cookie for session token
        setcookie('session_token', $token, $remember ? time()+60*60*24*30 : 0, '/');

        // Set PHP session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['first_name'];

        header('Location: http://localhost/h26/index.php'); exit;
    }

    // Record login attempt
    $ip   = $_SERVER['REMOTE_ADDR'];
    $ua   = $_SERVER['HTTP_USER_AGENT'] ?? null;
    $time = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("INSERT INTO logins 
        (user_id, login_time, ip_address, user_agent, success)
        VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $user['id'] ?? null,
        $time, $ip, $ua, $success
    ]);

    header('Location: login.html?error=' . urlencode('Invalid credentials.')); exit;
}

header('Location: http://localhost/h26/index.php');
exit;
?>