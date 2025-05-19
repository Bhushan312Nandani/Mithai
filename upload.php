<?php
require './cloudinary/cloudinary_php/src/Cloudinary.php';
require './cloudinary/cloudinary_php/src/Upload/Uploader.php';
require './cloudinary/cloudinary_php/src/Api.php';

\Cloudinary::config(array(
  "cloud_name" => "dyah52hpv",
  "api_key"    => "977951971976772",
  "api_secret" => "_-A4FPXdvYoMh-nMqmXMDiapssk"
));

// Database credentials
$host = 'localhost';
$db = 'your_database_name';
$user = 'your_database_user';
$pass = 'your_database_password';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_tmp = $_FILES['image']['tmp_name'];

    if (!empty($product_name) && !empty($description) && $price > 0 && is_uploaded_file($image_tmp)) {
        try {
            $result = \Cloudinary\Uploader::upload($image_tmp, [
                "folder" => "products"
            ]);

            $image_url = $result['secure_url'];

            $conn = new mysqli($host, $user, $pass, $db);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("INSERT INTO products (product_name, description, price, image_path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $product_name, $description, $price, $image_url);
            $stmt->execute();

            echo "Product uploaded successfully.";

            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            echo "Upload failed: " . $e->getMessage();
        }
    } else {
        echo "Please fill all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Product</title>
</head>
<body>
    <h2>Upload Product</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Product Name: <input type="text" name="product_name" required></label><br><br>
        <label>Description: <textarea name="description" required></textarea></label><br><br>
        <label>Price: <input type="number" name="price" step="0.01" required></label><br><br>
        <label>Image: <input type="file" name="image" accept="image/*" required></label><br><br>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
