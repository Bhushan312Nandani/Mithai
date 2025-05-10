<?php 
include 'db.php';
$cats = $mysqli->query("SELECT id,name FROM categories");  // :contentReference[oaicite:6]{index=6}
?>
<h2>Upload New Product</h2>
<form method="post" enctype="multipart/form-data">
  <!-- name/description/price same as above -->
  <label>Name:</label><br>
  <input name="name" placeholder="Product Name"><br><br>

  <label>Category:</label><br>
  <select name="category_id" required>

    <option value="">-- Select Category --</option>
    <?php while($cat = $cats->fetch_assoc()): ?>
     <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
    <?php endwhile; ?>
  </select><br><br>

 <label>Description:</label><br>
  <textarea name="description" rows="4" cols="50"></textarea><br><br>

  <label>Price:</label><br>
  <input name="price" placeholder="Price"><br><br>

  <label>Image:</label><br>
  <input type="file" name="image" accept="image/*" required><br><br>

  <button type="submit">Upload</button>
</form>

<?php
    require_once("upload.php");
  echo "<p>Product uploaded successfully.</p>";
?>
