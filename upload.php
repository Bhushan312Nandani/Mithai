<?php
require 'db.php';
if($_FILES['image']['error']===0){
  $dst = 'images/'.basename($_FILES['image']['name']);
  move_uploaded_file($_FILES['image']['tmp_name'], $dst);
  $stmt = $mysqli->prepare(
    "INSERT INTO products (category_id,name,price,description,image_path) VALUES (?,?,?,?,?)"
  );
  $stmt->execute([
    $_POST['category_id'],
    $_POST['name'],
    $_POST['price'],
    $_POST['description'],
    $dst
  ]);
}
header('Location: admin.php');
