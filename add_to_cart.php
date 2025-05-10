<?php
// add_to_cart.php
session_start();
$id = intval($_GET['id']);
$sid = session_id();
$mysqli->query("INSERT INTO cart_items (product_id,session_id) VALUES ($id,'$sid')");
header("Location: cart.php");