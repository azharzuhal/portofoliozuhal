<?php
session_start();
include 'config.php';

// Cek jika pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?error=unauthorized');
    exit;
}

// Cek jika ada ID portofolio yang diberikan
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data portofolio dari database
    $stmt = $pdo->prepare("SELECT * FROM portofolio WHERE id = ?");
    $stmt->execute([$id]);
    $portfolio = $stmt->fetch();

    if (!$portfolio) {
        echo "Portofolio tidak ditemukan.";
        exit;
    }
}

// Proses pengeditan portofolio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Update database
    $stmt = $pdo->prepare("UPDATE portofolio SET title = ?, description = ? WHERE id = ?");
    $stmt->execute([$title, $description, $id]);

    // Tambahkan parameter waktu untuk menghindari caching
    header('Location: listportofolio.php?t=' . time()); // Redirect setelah mengedit
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Portofolio</title>
    <link rel="stylesheet" href="csss/admin.css">
</head>
<body>
    <header>
        <h1>Edit Portofolio</h1>
    </header>
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <label for="title">Judul:</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($portfolio['title']); ?>" required>

            <label for="description">Deskripsi:</label>
            <textarea name="description" id="description" required><?= htmlspecialchars($portfolio['description']); ?></textarea>

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html> 