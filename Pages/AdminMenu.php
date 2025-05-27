<?php
session_start();

// Cek jika bukan admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../LoginRegister/FormLogin.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Menu - PLAYTOPIA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/homeCSS.css">
</head>
<body>
    <header class="playtopia-header">
        <button class="setting-btn">
            <span class="bar bar1"></span>
            <span class="bar bar2"></span>
            <span class="bar bar1"></span>
        </button>

        <div id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <h2>Admin Panel</h2>
                <button class="close-btn">&times;</button>
            </div>
            <ul>
                <li><a href="AddSongs.php">Tambah Lagu</a></li>
            </ul>
        </div>

        <script>
            const settingBtn = document.querySelector('.setting-btn');
            const sidebar = document.getElementById('sidebar');
            const closeBtn = document.querySelector('.close-btn');

            settingBtn.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });

            closeBtn.addEventListener('click', () => {
                sidebar.classList.remove('active');
            });
        </script>

        <div class="logo">
            <img src="../Assets/image/LogoPlaytopia1.png">
        </div>

        <nav class="nav-links">
            <a href="AdminMenu.php"><b>Dashboard</b></a>
            <a href="../LoginRegister/Logout.php"><b>Logout</b></a>
        </nav>
    </header>

    <main class="main-content">
        <section class="hero">
            <h1>Welcome, Admin <?= htmlspecialchars($_SESSION['username']); ?></h1>
            <p>Tambahkan lagu baru ke koleksi Playtopia.</p>
        </section>

        <section class="content-box">
            <div class="card-container">
                <a href="../Admin/InsertSong.php" class="card">➕ Add Song</a>
            </div>
        </section>

        <section class="content-box">
            <div class="card-container">
                <a href="../Admin/AllTabel.php" class="card">➕ All Table</a>
            </div>
        </section>

        <section class="content-box">
            <div class="card-container">
                <a href="../Admin/InsertAlbum.php" class="card">➕ Add Album</a>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2025 Playtopia Admin Panel. All rights reserved.</p>
    </footer>
</body>
</html>
