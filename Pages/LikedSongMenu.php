<?php
session_start();
include '../connection.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<p>❌ Please login to view liked songs.</p>";
    exit();
}

$idUser = $_SESSION['id_user'];

// Query: ambil lagu yang dilike oleh user
$query = "
    SELECT s.*
    FROM song_likes sl
    JOIN songs s ON sl.id_song = s.id_song
    WHERE sl.id_user = $idUser
";

$result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>🎧 Liked Songs</title>
    <link rel="stylesheet" href="../CSS/homeCSS.css"> <!-- Ganti jika pakai file CSS lain -->
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
                <li><a href="LikedSongMenu.php">Liked Songs</a></li>
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
            <img src="../Assets/image/LogoPlaytopia1.png" alt="Logo Playtopia">
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

    <section class="content-box">
        <h2>❤️ Liked Songs</h2>
        <div class="card-container">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="card" data-song-id="<?php echo $row['id_song']; ?>">
                        <img src="../Assets/image/<?php echo htmlspecialchars($row['cover_path']); ?>" alt="Cover">
                        <p class="title"><?php echo htmlspecialchars($row['title']); ?></p>
                        <p class="artist"><?php echo htmlspecialchars($row['artist']); ?></p>

                        <audio class="audio-player" src="../Assets/song/<?php echo htmlspecialchars($row['file_path']); ?>"
                            preload="none"></audio>
                        <button class="play-pause-btn">Play</button>

                        <div class="plays-like-row">
                            <p class="plays">Plays: <span
                                    class="plays-count"><?php echo isset($row['plays']) ? $row['plays'] : 0; ?></span></p>
                            <label class="container">
                                <input type="checkbox" class="like-checkbox" data-song-id="<?php echo $row['id_song']; ?>"
                                    checked>
                                <svg id="Layer_1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16.4,4C14.6,4,13,4.9,12,6.3C11,4.9,9.4,4,7.6,4C4.5,4,2,6.5,2,9.6C2,14,12,22,12,22s10-8,10-12.4C22,6.5,19.5,4,16.4,4z" />
                                </svg>
                            </label>
                        </div>

                        <input type="range" class="progress-bar" value="0" min="0" step="1">
                        <input type="range" class="volume-slider" min="0" max="1" step="0.01" value="0.5" title="Volume">
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>🎵 You haven’t liked any songs yet.</p>
            <?php endif; ?>
        </div>
    </section>

    
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

    </script>
</body>


</html>