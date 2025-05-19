<?php
include 'db.php';
$id = intval($_GET['id']);
$res = $mysqli->query("SELECT * FROM products WHERE id=$id");
$p = $res->fetch_assoc();
?>
<!DOCTYPE html><html><body>
<h2><?= htmlspecialchars($p['name']) ?></h2>
<img src="uploads/<?= $p['image'] ?>" alt=""><p><?= nl2br(htmlspecialchars($p['description'])) ?></p>
<p>₹ <?= $p['price'] ?></p>
<a href="add_to_cart.php?id=<?= $p['id'] ?>">Add to Cart</a>
</body></html>