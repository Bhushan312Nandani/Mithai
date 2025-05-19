<?php
// auth_check.php
require 'config.php';
if (empty($_SESSION['user_id'])) {
    header('Location: login.php'); exit;
}
// IP binding
if (ENABLE_IP_CHECK && $_SESSION['ip']!==$_SERVER['REMOTE_ADDR']) {
    session_unset(); session_destroy();
    header('Location: login.php'); exit;
}
// Timeout
if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
    session_unset(); session_destroy();
    header('Location: login.php?timeout=1'); exit;
}
// Update last activity
$_SESSION['last_activity']=time();
$mysqli->query("UPDATE sessions SET last_activity=".time()." WHERE id='".session_id()."'");



?>