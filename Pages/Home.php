<?php

include '../auth.php';
include '../connection.php';

$songQuery = "SELECT * FROM songs LIMIT 4"; // max 4 utk di home
$songResult = mysqli_query($connect, $songQuery);

if (!$songResult) {
    die("Query failed: " . mysqli_error($connect));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>PLAYTOPIA</title>
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

    <main class="main-content">
        <section class="hero">
            <h1>Welcome to Playtopia</h1>
            <p class="tagline"> Your Personal Music Playground </p>

            <?php
            if (isset($_SESSION['username'])) {
                date_default_timezone_set('Asia/Jakarta');
                $hour = date('H');
                $username = htmlspecialchars($_SESSION['username']);

                if ($hour >= 5 && $hour < 12) {
                    $greeting = "Morning, $username! Ready to start your day?";
                } elseif ($hour >= 12 && $hour < 17) {
                    $greeting = "Hey $username, need a soundtrack for your afternoon?";
                } elseif ($hour >= 17 && $hour < 21) {
                    $greeting = "Evening, $username! How was your day?";
                } else {
                    $greeting = "Still up, $username? Let the music keep you company tonight";
                }
                echo "<p class='welcome-user'>$greeting</p>";
            }
            ?>
        </section>

        <section class="content-box">
            <h2>🎵 Recommended for You</h2>
            <div class="card-container">
                <?php while ($row = mysqli_fetch_assoc($songResult)): ?>
                    <div class="card" data-song-id="<?php echo $row['id_song']; ?>">
                        <img src="../Assets/image/<?php echo htmlspecialchars($row['cover_path']); ?>" alt="Cover">
                        <p class="title"><?php echo htmlspecialchars($row['title']); ?></p>
                        <p class="artist"><?php echo htmlspecialchars($row['artist']); ?></p>

                        <audio class="audio-player" src="../Assets/song/<?php echo htmlspecialchars($row['file_path']); ?>"
                            preload="none">
                        </audio>

                        <button class="play-pause-btn">Play</button>

                        <div class="plays-like-row">
                            <p class="plays">Plays: <span
                                    class="plays-count"><?php echo isset($row['plays']) ? $row['plays'] : 0; ?></span></p>
                            <label class="container">
                                <?php
                                $checked = '';
                                $disabled = '';
                                $title = '';

                                if (isset($_SESSION['id_user'])) {
                                    $userId = $_SESSION['id_user'];
                                    $songId = $row['id_song'];
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
                                <input type="checkbox" class="like-checkbox" data-song-id="<?php echo $row['id_song']; ?>"
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
                <?php endwhile; ?>
            </div>
        </section>

        <!-- Top Charts -->
<section class="content-box">
    <h2>🔥 Top Charts</h2>
    <div class="card-container">
        <?php
        include '../connection.php';

        $query = "SELECT * FROM songs ORDER BY plays DESC LIMIT 4";
        $result = mysqli_query($connect, $query);

        while ($row = mysqli_fetch_assoc($result)):
            $idSong = $row['id_song'];
            $title = htmlspecialchars($row['title']);
            $artist = htmlspecialchars($row['artist']);
            $cover = htmlspecialchars($row['cover_path']);
            $audio = htmlspecialchars($row['file_path']);
            $plays = isset($row['plays']) ? $row['plays'] : 0;

            $checked = '';
            $disabled = '';
            $titleAttr = '';

            if (isset($_SESSION['id_user'])) {
                $userId = $_SESSION['id_user'];
                $checkLikeQuery = "SELECT * FROM song_likes WHERE id_user = $userId AND id_song = $idSong";
                $likeResult = mysqli_query($connect, $checkLikeQuery);
                if ($likeResult && mysqli_num_rows($likeResult) > 0) {
                    $checked = 'checked';
                }
            } else {
                $disabled = 'disabled';
                $titleAttr = "title='Login to like songs'";
            }
        ?>
            <div class="card" data-song-id="<?php echo $idSong; ?>">
                <img src="../Assets/image/<?php echo htmlspecialchars($row['cover_path']); ?>" alt="Cover">
                <p class="title"><?php echo $title; ?></p>
                <p class="artist"><?php echo $artist; ?></p>

                <audio class="audio-player" src="../Assets/song/<?php echo $audio; ?>" preload="none"></audio>
                <button class="play-pause-btn">Play</button>

                <div class="plays-like-row">
                    <p class="plays">Plays: <span class="plays-count"><?php echo $plays; ?></span></p>
                    <label class="container">
                        <input type="checkbox" class="like-checkbox"
                            data-song-id="<?php echo $idSong; ?>"
                            <?php echo $checked . ' ' . $disabled . ' ' . $titleAttr; ?>>
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
    </div>
</section>



        <!-- Genres -->
        <section class="content-box">
            <h2>🎧 Genres</h2>
            <div class="card-container">
                <div class="card">
                    <img src="../Assets/image/pop.jpg" alt="Pop">
                    <p class="title">Pop</p>
                </div>
                <div class="card">
                    <img src="../Assets/image/rock.jpg" alt="Rock">
                    <p class="title">Rock</p>
                </div>
                <div class="card">
                    <img src="../Assets/image/hiphop.jpg" alt="Hip-Hop">
                    <p class="title">Hip-Hop</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2025 Playtopia. All rights reserved.</p>
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

    </script>

</body>

</html>