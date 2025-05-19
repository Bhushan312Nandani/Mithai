<?php
// config.php
define('SESSION_TIMEOUT', 600);             // 10 minutes
define('ENABLE_IP_CHECK', true);
ini_set('session.use_strict_mode', 1);      // :contentReference[oaicite:0]{index=0}
session_name('MYSHOPSESSID');
session_start();



?>