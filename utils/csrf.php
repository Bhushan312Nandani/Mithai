<?php


// utils/csrf.php
function generateToken(){
    if(empty($_SESSION['csrf_token'])){
        $_SESSION['csrf_token']=bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function verifyToken($token){
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}








?>