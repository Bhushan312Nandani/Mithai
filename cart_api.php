<?php
// cart_api.php
session_start();
header('Content-Type: application/json');

// 1) Handle incoming actions
if (isset($_GET['action'], $_GET['id'])) {
    $pid = (int)$_GET['id'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    switch ($_GET['action']) {
        case 'addItem':
            $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + 1;
            break;
        case 'removeItem':
            unset($_SESSION['cart'][$pid]);
            break;
    }
}
if (isset($_GET['action'], $_GET['pid'], $_GET['operation']) && $_GET['action']==='update_qty') {
    $pid = (int)$_GET['pid'];
    $op  = $_GET['operation'];
    if (isset($_SESSION['cart'][$pid])) {
        if ($op === 'add') {
            $_SESSION['cart'][$pid]++;
        } else {
            $_SESSION['cart'][$pid] = max(1, $_SESSION['cart'][$pid] - 1);
        }
    }
}

// 2) Build the response
$mysqli = new mysqli('localhost','BhushanN','','mithai_shop');
if ($mysqli->connect_errno) {
    echo json_encode(['success'=>false,'error'=>'DB connect']); exit;
}

$pids = array_keys($_SESSION['cart'] ?? []);
$response = [
  'success'  => true,
  'newCount' => array_sum($_SESSION['cart'] ?? []),
  'products' => [],
  'subtotal' => 0.0
];

if (!empty($pids)) {
    $ph   = implode(',', array_fill(0, count($pids), '?'));
    $sql  = "SELECT id, product_name, price, description, image_path
               FROM products
              WHERE id IN ($ph)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($pids)), ...$pids);
    $stmt->execute();
    $res  = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $id      = $row['id'];
        $qty     = $_SESSION['cart'][$id];
        $lineTot = $row['price'] * $qty;
        $response['products'][] = [
            'id'           => $id,
            'product_name' => $row['product_name'],
            'description'  => $row['description'],
            'image_path'   => $row['image_path'],
            'price'        => round($row['price'],2),
            'qty'          => $qty,
            'lineTotal'    => round($lineTot,2)
        ];
        $response['subtotal'] += $lineTot;
    }
    $stmt->close();
}

$response['subtotal'] = round($response['subtotal'],2);
$mysqli->close();

echo json_encode($response);
exit;

?>