<?php
include '../auth.php';
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
    $songQuery = "SELECT *
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
            <li><a href="Playlist.php">Playlist</a></li>
            <li><a href="LikedSongMenu.php">Liked Songs</a></li>
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

            <h2>Songs in this Playlist 🎵</h2>
            <?php if (!empty($songs)): ?>
                <div class="card-container">
                    <?php foreach ($songs as $song): ?>
                        <div class="card" data-song-id="<?php echo $song['id_song']; ?>">
                            <img src="../Assets/image/<?php echo htmlspecialchars($song['cover_path']); ?>" alt="Cover">
                            <p class="title"><?php echo htmlspecialchars($song['title']); ?></p>
                            <p class="artist"><?php echo htmlspecialchars($song['artist']); ?></p>

                            <audio class="audio-player" src="../Assets/song/<?php echo htmlspecialchars($song['file_path']); ?>"
                                preload="none"></audio>
                            <button class="play-pause-btn">Play</button>

                            <div class="plays-like-row">
                                <p class="plays">Plays: <span
                                        class="plays-count"><?php echo isset($row['plays']) ? $song['plays'] : 0; ?></span></p>
                                <label class="container">
                                    <?php
                                    $checked = '';
                                    $disabled = '';
                                    $title = '';

                                    if (isset($_SESSION['id_user'])) {
                                        $userId = $_SESSION['id_user'];
                                        $songId = $song['id_song'];
                                        $checkLikeQuery = "SELECT * FROM song_likes WHERE id_user = $userId AND id_song = $songId";
                                        $likeResult = mysqli_query($connect, $checkLikeQuery);
                                        if ($likeResult && mysqli_num_rows($likeResult) > 0) {
                                            $checked = 'checked';
                                        }
                                    } else {
                                        $disabled = 'disabled';
                                        $title = "title='Login to like songs'";
                                    }
                                    ?>
                                    <input type="checkbox" class="like-checkbox" data-song-id="<?php echo $song ['id_song']; ?>"
                                        <?php echo $checked . ' ' . $disabled . ' ' . $title; ?>>
                                    <svg id="Layer_1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16.4,4C14.6,4,13,4.9,12,6.3C11,4.9,9.4,4,7.6,4C4.5,4,2,6.5,2,9.6C2,14,12,22,12,22s10-8,10-12.4C22,6.5,19.5,4,16.4,4z" />
                                    </svg>
                                </label>
                            </div>

                            <input type="range" class="progress-bar" value="0" min="0" step="1">
                            <input type="range" class="volume-slider" min="0" max="1" step="0.01" value="0.5" title="Volume">
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
                <?php foreach ($playlists as $p): 
                    $idPlaylist = $p['id_playlist'];
                    $checked = '';
                    $disabled = '';
                    $titleAttr = '';

                    if (isset($_SESSION['id_user'])) {
                        $userId = $_SESSION['id_user'];
                        $checkLikeQuery = "SELECT * FROM playlist_likes WHERE id_user = $userId AND id_playlist = $idPlaylist";
                        $likeResult = mysqli_query($connect, $checkLikeQuery);
                        if ($likeResult && mysqli_num_rows($likeResult) > 0) {
                            $checked = 'checked';
                        }
                    } else {
                        $disabled = 'disabled';
                        $titleAttr = "title='Login to like songs'";
                    }
                    ?>
                    <a href="Playlist.php?id=<?= $p['id_playlist'] ?>" class="card-link">
                      <div class="card">
                        <img src="<?= $p['cover_url'] ?>" alt="<?= $p['playlist_name'] ?>">
                        <div class="title"><?= $p['playlist_name'] ?></div>
                        <div class="artist">Created: <?= $p['created_at'] ?></div>
                        <div class="card-actions">
                          <label class="container">
                                <input type="checkbox" class="playlist-like-checkbox"
                                    data-playlist-id="<?php echo $idPlaylist; ?>"
                                    <?php echo $checked . ' ' . $disabled . ' ' . $titleAttr; ?>>
                                <svg id="Layer_1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16.4,4C14.6,4,13,4.9,12,6.3C11,4.9,9.4,4,7.6,4C4.5,4,2,6.5,2,9.6C2,14,12,22,12,22s10-8,10-12.4C22,6.5,19.5,4,16.4,4z" />
                                </svg>
                            </label>
                          <a href="SharePlaylist.php?id=<?= $p['id_playlist'] ?>" class="btn-share" onclick="event.stopPropagation();">🔗 Share</a>
                          <a href="CreatePlaylist.php?id=<?= $p['id_playlist'] ?>" class="btn-edit" onclick="event.stopPropagation();">✏️ Edit</a>
                          <form action="Delete_playlist.php?id=<?= $p['id_playlist'] ?>" method="POST" style="display:inline;" onsubmit="event.stopPropagation(); return confirm('Are you sure you want to delete this playlist?');">
                              <input type="hidden" name="id" value="<?= $p['id_playlist'] ?>">
                              <button type="submit" class="btn-delete" style="background:none;border:none;color:inherit;cursor:pointer;">🗑️ Delete</button>
                          </form>
                        </div>
                      </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>


<footer class="footer">
    <p style="text-align: center;">&copy; 2025 Playtopia. All rights reserved.</p>
</footer>


<script>
        const cards = document.querySelectorAll('.card');

        let currentlyPlayingAudio = null;
        let currentlyPlayingBtn = null;

        function updatePlaysCount(songId, playsElement) {
            fetch('Plays.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'song_id=' + songId
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        playsElement.textContent = data.plays;
                    } else {
                        console.error('Failed to update plays:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error updating plays:', error);
                });
        }

        cards.forEach((card, index) => {
            const audio = card.querySelector('.audio-player');
            const btn = card.querySelector('.play-pause-btn');
            const progressBar = card.querySelector('.progress-bar');
            const volumeSlider = card.querySelector('.volume-slider');
            const songId = card.dataset.songId;
            const playsElement = card.querySelector('.plays-count');

            if (!audio || !btn) return;

            let playsCounted = false;

            audio.addEventListener('loadedmetadata', () => {
                progressBar.max = Math.floor(audio.duration);
            });

            btn.addEventListener('click', () => {
                if (audio.paused) {
                    if (currentlyPlayingAudio && currentlyPlayingAudio !== audio) {
                        currentlyPlayingAudio.pause();
                        if (currentlyPlayingBtn) currentlyPlayingBtn.textContent = 'Play';
                    }

                    audio.play();
                    btn.textContent = 'Pause';
                    currentlyPlayingAudio = audio;
                    currentlyPlayingBtn = btn;

                    playsCounted = false;

                } else {
                    audio.pause();
                    btn.textContent = 'Play';
                    currentlyPlayingAudio = null;
                    currentlyPlayingBtn = null;
                }
            });

            audio.addEventListener('timeupdate', () => {
                progressBar.value = Math.floor(audio.currentTime);
            });

            progressBar.addEventListener('input', () => {
                audio.currentTime = progressBar.value;
            });

            volumeSlider.addEventListener('input', () => {
                audio.volume = volumeSlider.value;
            });

            audio.addEventListener('ended', () => {
                btn.textContent = 'Play';
                currentlyPlayingAudio = null;
                currentlyPlayingBtn = null;

                if (songId && playsElement && !playsCounted) {
                    updatePlaysCount(songId, playsElement);
                    playsCounted = true;
                }

                const nextIndex = index + 1;
                if (nextIndex < cards.length) {
                    const nextCard = cards[nextIndex];
                    const nextAudio = nextCard.querySelector('.audio-player');
                    const nextBtn = nextCard.querySelector('.play-pause-btn');

                    if (nextAudio && nextBtn) {
                        nextAudio.play();
                        nextBtn.textContent = 'Pause';

                        currentlyPlayingAudio = nextAudio;
                        currentlyPlayingBtn = nextBtn;

                        const nextSongData = cards[nextIndex];
                        if (nextSongData) {

                        }
                    }
                }
            });
        });

        document.querySelectorAll('.like-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const songId = checkbox.dataset.songId;
                const liked = checkbox.checked;

                fetch('Like_Song.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `song_id=${songId}&liked=${liked ? 1 : 0}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            alert('Failed to update like: ' + data.message);
                            checkbox.checked = !liked;
                        }
                    })
                    .catch(() => {
                        alert('Error connecting to server');
                        checkbox.checked = !liked;
                    });
            });
        });

        document.querySelectorAll('.playlist-like-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const playlistId = checkbox.dataset.playlistId;
                const liked = checkbox.checked;

                fetch('Like_Playlist.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `playlist_id=${playlistId}&liked=${liked ? 1 : 0}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            alert('Failed to update like: ' + data.message);
                            checkbox.checked = !liked;
                        }
                    })
                    .catch(() => {
                        alert('Error connecting to server');
                        checkbox.checked = !liked;
                    });
            });
        });

    </script>
</body>

</html>
