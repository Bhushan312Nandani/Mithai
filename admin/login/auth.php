<?php
// signup.php - Handles user sign-up (template unchanged)
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
    $first = trim($_POST['name'] ?? '');
    $last  = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $passw = trim($_POST['pwd'] ?? '');
    $remember = isset($_POST['remember']) ? 1 : 0;
    $errors = [];

    if (!$first)  $errors[] = 'First name required.';
    if (!$last)   $errors[] = 'Last name required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
    if (strlen($passw) < 6) $errors[] = 'Password min 6 chars.';

    if (!$errors) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) $errors[] = 'Email exists.';
    }

    if ($errors) {
        header('Location: signup.html?error=' . urlencode(implode(' ', $errors)));
        exit;
    }

    $hash = password_hash($passw, PASSWORD_DEFAULT);
    $now  = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare("INSERT INTO users 
        (first_name, last_name, email, password, remember_me, created_at)
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$first, $last, $email, $hash, $remember, $now]);

    header('Location: login.html?signup=success'); exit;
}

header('Location: signup.html'); exit;
?>
