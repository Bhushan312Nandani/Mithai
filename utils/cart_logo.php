<?php
session_start();
// Handle AJAX “add” or “remove” calls immediately:
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'add') {
  $pid = (int)$_GET['id'];
  $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + 1;

  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode([
      'success' => true,
      'newCount' => array_sum($_SESSION['cart'])
    ]);
    exit;
  }

  header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
  exit;
}

// Otherwise page is being loaded normally, just compute current count
$count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}
$count = array_sum($_SESSION['cart']);
?>

  <ul>
     <li><a href="cart.php">
     <div class="hover02 column" style="display:inline; ">
    <img src="./utils/cart-removebg-preview.png" style="width:30px;height:30px;">
      </div>
     <?php
   $count = 0;
   if (!empty($_SESSION['cart'])) {
     foreach ($_SESSION['cart'] as $v) {
       $count += $v;
     }
     ?>
      <span id="cart_count" class="text-light bg-danger rounded-0"><?=$count ?> </span>
      <?php 
  }else{
      echo "<span id=\"cart_count\" class=\"text-light bg-danger rounded-0\"></span>";
  }
  ?>
        <span class="padding_10">Cart</span></a>
     </li>
  </ul>