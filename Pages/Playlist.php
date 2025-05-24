<?php
session_start();
include '../connection.php';

$userId = $_SESSION['id_user'];

$query = "SELECT * FROM playlists WHERE id_user = $userId";
$result = mysqli_query($connect, $query);
$playlists = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $playlists[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Playlists</title>
  <link rel="stylesheet" href="../CSS/Playlist.css">
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
            <h2>Library</h2>
            <button class="close-btn">&times;</button>
        </div>
        <ul>
            <li><a href="../Pages/Playlist.php">Playlist</a></li>
            <li><a href="#">Liked Songs</a></li>
            <li><a href="#">Recently Played</a></li>
            <li><a href="#">Albums</a></li>
            <li><a href="#">Artists</a></li>
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
        <img src="../Assets/image/LogoPlaytopia1.png" alt="Logo">
    </div>

    <nav class="nav-links">
        <a href="Home.php"><b>Home</b></a>
        <a href="Search.php"><b>Search</b></a>
        <?php if (isset($_SESSION['id_user'])): ?>
            <a href="../LoginRegister/Logout.php"><b>Logout</b></a>
        <?php else: ?>
            <a href="../LoginRegister/FormRegister.html"><b>Register</b></a>
            <a href="../LoginRegister/FormLogin.html"><b>Login</b></a>
        <?php endif; ?>
    </nav>
</header>

<main class="main-content">
    <h2>🎧 Your Playlists</h2>

    <div class="action-bar">
      <a href="CreatePlaylist.php" class="add-btn">➕ Add New Playlist</a>
    </div>

    <?php if (empty($playlists)): ?>
      <p class="no-playlist">No playlists yet.</p>
    <?php else: ?>
      <div class="card-container">
        <?php foreach ($playlists as $playlist): ?>
          <div class="card">
            <img src="<?= $playlist['cover_url'] ?>" alt="<?= $playlist['playlist_name'] ?>">
            <div class="title"><?= $playlist['playlist_name'] ?></div>
            <div class="artist">Created: <?= $playlist['created_at'] ?></div>
            <div class="card-actions">
              <a href="add_song_to_playlist.php?id=<?= $playlist['id_playlist'] ?>" class="btn-secondary">+ Add Song</a>
              <a href="like_playlist.php?id=<?= $playlist['id_playlist'] ?>" class="btn-like">❤️ Like</a>
              <a href="share_playlist.php?id=<?= $playlist['id_playlist'] ?>" class="btn-share">🔗 Share</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
</main>

<footer class="footer">
    <p>&copy; 2025 Playtopia. All rights reserved.</p>
</footer>
</body>
</html>
