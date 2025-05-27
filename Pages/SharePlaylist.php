<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('You must log in first.'); window.location.href='../LoginRegister/FormLogin.html';</script>";
    exit;
}

$userId = $_SESSION['id_user'];
$playlistId = intval($_GET['id'] ?? 0);

if (!$playlistId) {
    echo "<script>alert('Invalid playlist.'); window.location.href='Playlist.php';</script>";
    exit;
}

// Fetch original playlist
$playlistRes = mysqli_query($connect, "SELECT * FROM playlists WHERE id_playlist=$playlistId AND id_user=$userId");
if (!$playlistRes || mysqli_num_rows($playlistRes) === 0) {
    echo "<script>alert('Playlist not found.'); window.location.href='Playlist.php';</script>";
    exit;
}
$playlist = mysqli_fetch_assoc($playlistRes);

// Fetch playlist songs
$songsRes = mysqli_query($connect, "SELECT id_song FROM playlists_songs WHERE id_playlist=$playlistId");
$songIds = [];
while ($row = mysqli_fetch_assoc($songsRes)) {
    $songIds[] = $row['id_song'];
}

// Fetch accepted friends
$friendQuery = "
    SELECT u.id_user, u.username
    FROM users u
    INNER JOIN friends f
        ON ((f.id_user1 = $userId AND f.id_user2 = u.id_user) OR (f.id_user2 = $userId AND f.id_user1 = u.id_user))
        AND f.status = 'accepted'
    WHERE u.id_user != $userId
";
$friendsResult = mysqli_query($connect, $friendQuery);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['friend_ids'])) {
    $friendIds = $_POST['friend_ids'];
    $createdAt = date('Y-m-d H:i:s');

    foreach ($friendIds as $friendId) {
        $friendId = intval($friendId);

        // Create copy of playlist
        $nameCopy = $playlist['playlist_name'] . " (Shared)";
        $descCopy = "Shared from user ID $userId: " . $playlist['description'];
        $coverCopy = $playlist['cover_url'];

        $insertPlaylist = "INSERT INTO playlists (id_user, playlist_name, description, cover_url, created_at)
                           VALUES ($friendId, '$nameCopy', '$descCopy', '$coverCopy', '$createdAt')";
        mysqli_query($connect, $insertPlaylist);
        $newPlaylistId = mysqli_insert_id($connect);

        // Copy songs
        foreach ($songIds as $songId) {
            mysqli_query($connect, "INSERT INTO playlists_songs (id_playlist, id_song) VALUES ($newPlaylistId, $songId)");
        }
    }

    echo "<script>alert('Playlist shared successfully!'); window.location.href='Playlist.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Share Playlist</title>
    <link rel="stylesheet" href="../CSS/SharePlaylist.css"> <!-- Style later -->
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
              <li><a href="Profile.php">Profile</a></li>
              <li><a href="Friends.php">Friends</a></li>
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
  <main class="share-playlist-container">
    <h2>Share Playlist: <?= htmlspecialchars($playlist['playlist_name']) ?></h2>
    <form method="POST" action="">
      <p>Select friends to share this playlist with:</p>
      <?php if ($friendsResult && mysqli_num_rows($friendsResult) > 0): ?>
        <?php while ($friend = mysqli_fetch_assoc($friendsResult)): ?>
          <label>
          <input type="checkbox" name="friend_ids[]" value="<?= $friend['id_user'] ?>" />
          <?= htmlspecialchars($friend['username']) ?>
          </label><br>
        <?php endwhile; ?>
        <button type="submit">Share Playlist</button>
        <?php else: ?>
        <p>You have no accepted friends to share with.</p>
      <?php endif; ?>
    </form>
    <a href="Playlist.php" class="back-link">← Back to Playlists</a>
  </main>

  <footer class="footer">
      <p>&copy; 2025 Playtopia. All rights reserved.</p>
  </footer>
</body>
</html>
