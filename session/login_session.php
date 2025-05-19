<?php
// login.php (simplified)
require 'config.php';
$mysqli = new mysqli(...);
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $u = $_POST['username'];
    $p = $_POST['password'];
    $stmt = $mysqli->prepare("SELECT id,password_hash FROM users WHERE username=?");
    $stmt->bind_param('s', $u);
    $stmt->execute(); $stmt->bind_result($uid,$phash);
    if ($stmt->fetch() && password_verify($p,$phash)) {
        session_regenerate_id(true);                     // :contentReference[oaicite:1]{index=1}
        $_SESSION['user_id'] = $uid;
        $_SESSION['ip']      = $_SERVER['REMOTE_ADDR'];
        $_SESSION['last_activity'] = time();
        // Persist to sessions table:
        $sid = session_id();
        $ip  = $_SESSION['ip'];
        $ts = $_SESSION['last_activity'];
        $up = $mysqli->prepare("REPLACE INTO sessions (id,user_id,ip_address,last_activity) VALUES (?,?,?,?)");
        $up->bind_param('sisi',$sid,$uid,$ip,$ts);
        $up->execute();
        header('Location:index.php'); exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>