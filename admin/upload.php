<?php
// upload.php - process upload form from admin/upload.html and insert into MySQL database

// Database configuration
$host = 'localhost';
$db   = 'mithai_shop';
$user = 'BhushanN';
$pass = '';
$charset = 'utf8mb4';

// DSN and options for PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Database Connection Failed: ' . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize inputs
    $name        = trim($_POST['name'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $price       = trim($_POST['price'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Validate required fields
    $errors = [];
    if ($name === '')        { $errors[] = 'Name is required.'; }
    if ($category === '')    { $errors[] = 'Category is required.'; }
    if ($price === '' || !is_numeric($price)) { $errors[] = 'Valid price is required.'; }

    // Handle file upload if provided
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = __DIR__ . '/../uploads/';  // adjust path as needed
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $fileName    = basename($_FILES['image']['name']);
        $targetFile  = $targetDir . $fileName;
        $fileTmpName = $_FILES['image']['tmp_name'];
        $fileSize    = $_FILES['image']['size'];
        $fileError   = $_FILES['image']['error'];
        $fileType    = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validate file type
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileType, $allowed)) {
            $errors[] = 'Only JPG, JPEG, PNG & GIF files are allowed.';
        }
        if ($fileError !== UPLOAD_ERR_OK) {
            $errors[] = 'Error uploading image.';
        }
        if ($fileSize > 5 * 1024 * 1024) {
            $errors[] = 'Image size must be less than 5MB.';
        }

        if (empty($errors)) {
            // Move uploaded file
            if (move_uploaded_file($fileTmpName, $targetFile)) {
                // Store relative path for database
                $imagePath = 'uploads/' . $fileName;
            } else {
                $errors[] = 'Failed to move uploaded file.';
            }
        }
    }

    if (empty($errors)) {
        // Insert into database
        $stmt = $pdo->prepare('INSERT INTO sweets (name, category, price, description, image_path) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$name, $category, $price, $description, $imagePath]);

        // On success, redirect back or show message
        header('Location: upload.html?success=1');
        exit;
    }
}

// If errors or GET request, redirect back with errors (or you can display errors here)
if (!empty($errors)) {
    $errStr = urlencode(implode(" ", $errors));
    header("Location: admin/upload.html?error=$errStr");
    exit;
}

// If accessed directly without POST, redirect to form
header('Location: admin/upload.html');
exit;
