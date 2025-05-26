<?php
session_start();
include '../connection.php';

$userId = $_SESSION['id_user'];
$playlistId = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($playlistId) {
    // Get single playlist details
    $query = "SELECT * FROM playlists WHERE id_user = $userId AND id_playlist = $playlistId";
    $result = mysqli_query($connect, $query);
    $playlist = mysqli_fetch_assoc($result);

    //function seconds formatting
    function formatDuration($seconds) {
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        return sprintf("%d:%02d", $minutes, $remainingSeconds);
    }

    // Get songs in the playlist
    $songQuery = "SELECT s.title, s.artist, s.duration 
                  FROM songs s
                  JOIN playlists_songs ps ON s.id_song = ps.id_song
                  WHERE ps.id_playlist = $playlistId";
    $songResult = mysqli_query($connect, $songQuery);
    $songs = [];
    if ($songResult && mysqli_num_rows($songResult) > 0) {
        while ($row = mysqli_fetch_assoc($songResult)) {
            $songs[] = $row;
        }
    }
} else {
    // Show all playlists
    $query = "SELECT * FROM playlists WHERE id_user = $userId";
    $result = mysqli_query($connect, $query);
    $playlists = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $playlists[] = $row;
        }
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
                <li><a href="../Pages/LikedSongMenu.php">Liked Songs</a></li>
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

<main class="main-content">
    <?php if ($playlistId && isset($playlist)): ?>
        <div class="single-playlist">
            <div class="playlist-header">
                <img src="<?= $playlist['cover_url'] ?>" alt="<?= $playlist['playlist_name'] ?>" class="playlist-cover">
                <div class="playlist-info">
                    <h2><?= $playlist['playlist_name'] ?></h2>
                    <p><?= $playlist['description'] ?></p>
                    <p><small>Created: <?= $playlist['created_at'] ?></small></p>
                </div>
            </div>

            <h3>Songs in this Playlist 🎵</h3>
            <?php if (!empty($songs)): ?>
                <div class="song-list">
                    <?php foreach ($songs as $song): ?>
                        <div class="song-item">
                            <div class="song-title"><?= $song['title'] ?></div>
                            <div class="song-artist"><?= $song['artist'] ?></div>
                            <div class="song-duration"><?= formatDuration($song['duration']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>This playlist has no songs yet.</p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <h2>🎧 Your Playlists</h2>

        <div class="action-bar">
            <a href="CreatePlaylist.php" class="add-btn">➕ Add New Playlist</a>
        </div>

        <?php if (empty($playlists)): ?>
            <p class="no-playlist">No playlists yet.</p>
        <?php else: ?>
            <div class="card-container">
                <?php foreach ($playlists as $p): ?>
                    <a href="Playlist.php?id=<?= $p['id_playlist'] ?>" class="card-link">
                      <div class="card">
                        <img src="<?= $p['cover_url'] ?>" alt="<?= $p['playlist_name'] ?>">
                        <div class="title"><?= $p['playlist_name'] ?></div>
                        <div class="artist">Created: <?= $p['created_at'] ?></div>
                        <div class="card-actions">
                          <a href="add_song_to_playlist.php?id=<?= $p['id_playlist'] ?>" class="btn-secondary" onclick="event.stopPropagation();">+ Add Song</a>
                          <a href="like_playlist.php?id=<?= $p['id_playlist'] ?>" class="btn-like" onclick="event.stopPropagation();">❤️ Like</a>
                          <a href="share_playlist.php?id=<?= $p['id_playlist'] ?>" class="btn-share" onclick="event.stopPropagation();">🔗 Share</a>
                        </div>
                      </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>


<footer class="footer">
    <p>&copy; 2025 Playtopia. All rights reserved.</p>
</footer>
</body>
</html>
