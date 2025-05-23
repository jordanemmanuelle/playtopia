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
  <div class="main-content">
    <a href="Home.php" class="btn-back-home" style="display: inline-block; margin-bottom: 15px; text-decoration: none; color: #fff; background-color: #007bff; padding: 8px 15px; border-radius: 5px;">← Back to Home</a>

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
  </div>
</body>
</html>
