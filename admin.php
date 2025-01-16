<?php
session_start();
include 'config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?error=unauthorized');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image = ''; // Inisialisasi variabel untuk gambar

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = './uploaded_files/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $stmt = $pdo->prepare("INSERT INTO portofolio (title, description, image) VALUES (?, ?, ?)");
                $stmt->execute([$title, $description, $dest_path]);
                header('Location: listportofolio.php');
                exit;
            } else {
                $error = "Error saat memindahkan file.";
            }
        } else {
            $error = "File tidak diizinkan. Hanya file: " . implode(', ', $allowedfileExtensions);
        }
    }
}

$portfolios = $pdo->query("SELECT * FROM portofolio")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="csss\admin.css">
</head>
<body>
    <header>
        <h1>Admin Panel</h1>
        <a href="logout.php">Logout</a>
        <a href="listportofolio.php">list portofolio</a>
        <a href="index.php">Beranda</a>
    </header>
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <label>Title: <input type="text" name="title" required></label><br><br>
            <label>Description: <textarea name="description" required></textarea></label><br><br>
            <label>Image: <input type="file" name="image" required></label><br><br>
            <button type="submit">Add Portfolio</button>
        </form>
        
    </div>
</body>
</html>
