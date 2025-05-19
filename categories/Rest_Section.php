<?php
$mysqli = new mysqli('localhost', 'BhushanN', '', 'mithai_shop');
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Only these two categories
$allowed = ['Milk-Based', 'Fried'];

$catQuery = "SELECT id, name FROM categories WHERE name NOT IN ('Milk-Based', 'Fried')";
$catResult = $mysqli->query($catQuery);

$categories = [];
while ($c = $catResult->fetch_assoc()) {
    $slug = str_replace(' ', '-', $c['name']);
    $categories[$c['id']] = ['name' => $c['name'], 'slug' => $slug, 'products' => []];
}

$productQuery = "SELECT * FROM products WHERE category_id IN (" . implode(',', array_keys($categories)) . ")";
$productResult = $mysqli->query($productQuery);
while ($p = $productResult->fetch_assoc()) {
    $categories[$p['category_id']]['products'][] = $p;
}

$mysqli->close();

foreach ($categories as $cat) {
    if (empty($cat['products'])) continue;
    $chunks = array_chunk($cat['products'], 3);
    $sliderId = $cat['slug'] . '_main_slider';
?>
<div style="margin:0px">
<div class="fashion_section" id="<?= $cat['slug'] ?>">
  <div id="<?= $sliderId ?>" class="carousel slide" data-ride="carousel" data-interval="false">
    <div class="carousel-inner">
      <?php foreach ($chunks as $i => $group): ?>
      <div class="carousel-item<?= $i === 0 ? ' active' : '' ?>">
        <div class="container">
          <h1 class="fashion_taital"><?= $cat['name'] ?> Sweets</h1>
          <div class="fashion_section_2">
            <div class="row">
              <?php foreach ($group as $p): ?>
              <div class="col-lg-4 col-sm-6">
                <div class="single-product">
                  <div class="product-image">
                    <img src="<?= $p['image_path'] ?>" style="width:337px;height:335px;">
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
                    <span class="category"><?= $cat['name'] ?></span>
                    <h4 class="title">
                      <a href="product-details.php?id=<?= $p['id'] ?>"><?= $p['product_name'] ?></a>
                    </h4>
                    <ul class="review">
                      <li><i class="lni lni-star-filled"></i></li>
                      <li><i class="lni lni-star-filled"></i></li>
                      <li><i class="lni lni-star-filled"></i></li>
                      <li><i class="lni lni-star"></i></li>
                      <li><span>4.0 Review(s)</span></li>
                    </ul>
                    <div class="price">
                      <span>PKR <?= number_format($p['price'], 2) ?></span>
                    </div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="margin:20px;padding:10px; display:inline;">
<a class="carousel-control-prev" href="#<?= $sliderId ?>" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#<?= $sliderId ?>" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
</div>
  </div>
</div>
</div>




<?php } ?>
