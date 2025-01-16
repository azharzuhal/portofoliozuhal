<?php
session_start();
include 'config.php';

// Ambil data portofolio dari database
$stmt = $pdo->query("SELECT * FROM portofolio");
$portfolios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Portofolio Zuhal</title>
    <link rel="stylesheet" href="csss/index.css">
</head>
<body>
    <header>
        <h1>Portofolio Zuhal</h1>
        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="admin.php" class="admin-button">Admin</a> <!-- Tampilkan tombol Admin jika login -->
                <a href="logout.php" class="logout-button">Logout</a>
            <?php else: ?>
                <a href="login.php" class="login-button">Login</a>
            <?php endif; ?>
        </div>
    </header>
    <section class="hero">
        <h2>Selamat Datang di Portofolio Saya</h2>
    </section>
    <section class="profile">
        <img src="image/fotoku.JPG" alt="Profile Image" class="profile-image">
        <div class="profile-info">
            <h3>Azhartamma Zuhal Budiazka</h3>
            <p>Mahasiswa Informatika Semester 3 Universitas Islam Indonesia.</p>
        </div>
    </section>
    <section class="projects">
        <h2>Portofolio Saya</h2>
        <div class="portfolio-grid">
            <?php foreach ($portfolios as $portfolio): ?>
                <!-- Menampilkan Portofolio -->
                <div class="portfolio-item">
                    <img src="<?= htmlspecialchars($portfolio['image']); ?>" alt="Image">
                    <h3><?= htmlspecialchars($portfolio['title']); ?></h3>
                    <p><?= htmlspecialchars($portfolio['description']); ?></p>
                    <p class="portfolio-time">
                        <?= date('d M Y, H:i', strtotime($portfolio['uploaded_files'])); ?> <!-- Dibuat pada:  -->
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <footer>
        <p>© 2024 Portofolio Saya. Hak Cipta Dilindungi.</p>
    </footer>
</body>
</html>