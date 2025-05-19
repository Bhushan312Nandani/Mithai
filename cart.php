<?php
// cart_ui.php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Order</title>
  <link
    rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
  >
  <link href="styles.css?v=./css/responsive.css" rel="stylesheet">
  <link href="styles.css?v=./css/style.css" rel="stylesheet">
  <link href="styles.css?v=./css/single.css" rel="stylesheet">
  <style>
    .empty-cart { text-align:center; padding:80px; color:#777; }
    .cart-items .card { margin-bottom:1rem; }
  </style>

</head>
<?php require './utils/head.php'; ?>
<body>

  <!-- your site header -->
  <?php require './utils/header.php'; ?>

  <div class="container py-5" id="cart-container">
    <h2>My Shopping Cart</h2>
    <hr>
    <!-- content will be injected here -->
  </div>

  <?php require './utils/footer.php'; ?>

  <script>
  async function refreshCart() {
    try {
      const res  = await fetch('cart_api.php');
      const data = await res.json();
      const root = document.getElementById('cart-container');
      root.innerHTML = '<h2>My Shopping Cart</h2><hr>';

      if (!data.success || !data.products.length) {
        root.innerHTML += `
          <div class="empty-cart">
            <h4>You have not added any product to cart yet.</h4>
            <p class="text-muted">Browse our categories and click “Add to Cart” to start shopping.</p>
            <a href="index.php" class="btn btn-outline-primary">Return to Shop</a>
          </div>`;
        updateBadge(data.newCount);
        return;
      }

      // build items column
      let html = `<div class="row">
        <div class="col-md-8">`;
      data.products.forEach(p => {
        html += `
          <div class="cart-items card">
            <div class="row no-gutters align-items-center">
              <div class="col-md-3">
                <img src="${p.image_path}"
                     class="card-img"
                     style="width:100%;object-fit:cover"
                     alt="${p.product_name}">
              </div>
              <div class="col-md-6">
                <div class="card-body">
                  <h5 class="card-title">${p.product_name}</h5>
                  <p class="card-text">${p.description}</p>
                  <p class="card-text">
                    <small class="text-muted">
                      PKR ${p.price.toFixed(2)} × ${p.qty} = 
                      PKR ${p.lineTotal.toFixed(2)}
                    </small>
                  </p>
                  <button class="btn btn-sm btn-danger btn-remove" data-id="${p.id}">
                    Remove
                  </button>
                </div>
              </div>
              <div class="col-md-3 text-center">
                <div class="btn-group" role="group">
                <div style="padding:2px;">
                <button class="btn btn-outline-secondary btn-update" data-id="${p.id}" data-op="minus">−</button>
                </div>
                  <span class="btn btn-light">${p.qty}</span>
                  <div style="padding:2px;">
                  <button class="btn btn-outline-secondary btn-update" data-id="${p.id}" data-op="add">+</button>
                  </div>
                </div>
              </div>
            </div>
          </div>`;
      });
      html += `</div>

      <div class="col-md-4">
        <div class="card p-3">
          <h5 class="mb-3">Order Summary</h5>
          <p>Subtotal: <strong>PKR ${data.subtotal.toFixed(2)}</strong></p>
          <p>Delivery: <strong class="text-success">FREE</strong></p>
          <hr>
          <h4>Total: PKR ${data.subtotal.toFixed(2)}</h4>
          <a href="checkout.php" class="btn btn-primary btn-block mt-3">Proceed to Checkout</a>
        </div>
      </div>
      </div>`;

      root.innerHTML += html;
      updateBadge(data.newCount);

      // attach event listeners
      document.querySelectorAll('.btn-update').forEach(btn => {
        btn.onclick = () => {
          const id = btn.dataset.id;
          const op = btn.dataset.op;
          fetch(`cart_api.php?action=update_qty&pid=${id}&operation=${op}`)
            .then(_=> refreshCart());
        };
      });
      document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.onclick = () => {
          const id = btn.dataset.id;
          fetch(`cart_api.php?action=removeItem&id=${id}`)
            .then(_=> refreshCart());
        };
      });

    } catch (err) {
      console.error('Error loading cart:', err);
    }
  }

  function updateBadge(count) {
    const badge = document.getElementById('cart_count');
    if (badge) badge.textContent = count;
  }

  document.addEventListener('DOMContentLoaded', () => {
    refreshCart();
  });
  </script>

</body>
</html>
