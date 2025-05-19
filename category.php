<?php
// category.php

// 1) Bootstrap your DB connection
$mysqli = new mysqli('localhost','BhushanN','','mithai_shop');
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 2) Determine category slug from ?category=Milk-Based, etc.
$slug = isset($_GET['category']) 
      ? $mysqli->real_escape_string($_GET['category']) 
      : 'Milk-Based';

// 3) Look up the category record
$catSql = "
  SELECT id, name
    FROM categories
   WHERE REPLACE(name,' ','-') = '$slug'
  LIMIT 1
";
$catRes = $mysqli->query($catSql);
if (!$catRes || $catRes->num_rows === 0) {
    die("Category not found.");
}
$catRow      = $catRes->fetch_assoc();
$categoryId  = (int)$catRow['id'];
$categoryName= $catRow['name'];

// 4) Fetch **all** products in that category
$prodSql = "
  SELECT id, product_name, price, description, image_path
    FROM products
   WHERE category_id = $categoryId
   ORDER BY product_name
";
$prodRes = $mysqli->query($prodSql);
$products = [];
while ($p = $prodRes->fetch_assoc()) {
    $products[] = $p;
}
$mysqli->close();

// 5) Prepare our slices: first 3, then all
$firstThree = array_slice($products, 0, 3);
$allItems   = $products;

// 6) Build slider ID
$sliderId   = str_replace(' ','-',$categoryName) . '_main_slider';

?>

<!DOCTYPE html>
<html lang="en">
  <?php require_once './utils/head.php'; ?>
  <link href="styles.css?v=./css/responsive.css" rel="stylesheet">
      <link href="styles.css?v=./css/style.css" rel="stylesheet">
      <link href="styles.css?v=./css/single.css" rel="stylesheet">
  <body>
    <?php require_once './utils/header.php'; ?>

    <!-- <?= $categoryName ?> Section -->
    <div class="fashion_section" id="<?= $slug ?>">
      <div id="<?= $sliderId ?>" class="carousel slide" data-ride="carousel" data-interval="false">
        <div class="carousel-inner">

          <!-- Slide 1: first three -->
          <div class="carousel-item active">
            <div class="container">
              <h1 class="fashion_taital"><?= htmlspecialchars($categoryName) ?> Sweets</h1>
              <div class="fashion_section_2">
                <div class="row">
                  <?php foreach ($firstThree as $p): ?>
                    <div class="col-lg-4 col-sm-6">
                      <div class="single-product">
                        <div class="product-image">
                          <img src="<?= htmlspecialchars($p['image_path']) ?>"
                               style="width:330px;height:330px;"
                               alt="<?= htmlspecialchars($p['product_name']) ?>">
                          <div class="button">
                          <a
            href="utils/cart_logo.php?action=add&id=<?= $p['id'] ?>"
            class="btn add-to-cart glow-on-hover"
           data-id="<?= $p['id'] ?>">
          <i class="lni lni-cart"></i> Add to Cart
              </a>
                          </div>
                        </div>
                        <div class="product-info">
                          <span class="category"><?= htmlspecialchars($categoryName) ?></span>
                          <h4 class="title">
                            <a href="product-details.php?id=<?= $p['id'] ?>">
                              <?= htmlspecialchars($p['product_name']) ?>
                            </a>
                          </h4>
                          <ul class="review">
                            <li><i class="lni lni-star-filled"></i></li>
                            <li><i class="lni lni-star-filled"></i></li>
                            <li><i class="lni lni-star-filled"></i></li>
                            <li><i class="lni lni-star"></i></li>
                            <li><span>4.0 Review(s)</span></li>
                          </ul>
                          <div class="price">
                            <span>PKR <?= number_format($p['price'],2) ?></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 2: all products -->
          <div class="carousel-item">
            <div class="container">
              <h1 class="fashion_taital"><?= htmlspecialchars($categoryName) ?> Sweets</h1>
              <div class="fashion_section_2">
                <div class="row">
                  <?php foreach ($allItems as $p): ?>
                    <div class="col-lg-4 col-sm-6">
                      <div class="single-product">
                        <div class="product-image">
                          <img src="<?= htmlspecialchars($p['image_path']) ?>"
                               style="width:337px;height:335px;"
                               alt="<?= htmlspecialchars($p['product_name']) ?>">
                          <div class="button">
                          <a
            href="utils/cart_logo.php?action=add&id=<?= $p['id'] ?>"
            class="btn add-to-cart glow-on-hover"
           data-id="<?= $p['id'] ?>">
          <i class="lni lni-cart"></i> Add to Cart
              </a>
                          </div>
                        </div>
                        <div class="product-info">
                          <span class="category"><?= htmlspecialchars($categoryName) ?></span>
                          <h4 class="title">
                            <a href="product-details.php?id=<?= $p['id'] ?>">
                              <?= htmlspecialchars($p['product_name']) ?>
                            </a>
                          </h4>
                          <ul class="review">
                            <li><i class="lni lni-star-filled"></i></li>
                            <li><i class="lni lni-star-filled"></i></li>
                            <li><i class="lni lni-star-filled"></i></li>
                            <li><i class="lni lni-star"></i></li>
                            <li><span>4.0 Review(s)</span></li>
                          </ul>
                          <div class="price">
                            <span>PKR <?= number_format($p['price'],2) ?></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>

        </div><!-- /.carousel-inner -->

        <!-- Controls -->
        <div style="padding:20px; margin-top: 0px; margin-bottom: 25px;">
    <a class="carousel-control-prev" href="#<?= $sliderId ?>" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#<?= $sliderId ?>" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
    </div>

      </div><!-- /#<?= $sliderId ?> -->
    </div><!-- /.fashion_section -->



    <?php require_once './utils/footer.php'; ?>

    <!-- Add‑to‑Cart AJAX handler (same as before) -->
    <script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.add-to-cart').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();

      const pid = link.dataset.id;

      fetch(`utils/cart_logo.php?action=add&id=${pid}`, {
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'  // Important: triggers AJAX response
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success && data.newCount !== undefined) {
          const badge = document.getElementById('cart_count');
          if (badge) {
            badge.textContent = data.newCount;
          }
        } else {
          console.error("Cart update failed", data);
        }
      })
      .catch(error => console.error("AJAX Error:", error));
    });
  });
});
</script>
  </body>
</html>
