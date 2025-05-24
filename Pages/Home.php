<?php
    session_start();
    include '../connection.php';

    $songQuery = "SELECT * FROM songs LIMIT 4"; // max 4 utk di home
    $songResult = mysqli_query($connect, $songQuery);
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
            <img src="../Assets/image/LogoPlaytopia1.png">
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
        date_default_timezone_set('Asia/Jakarta'); // Adjust to your timezone
        $hour = date('H');
        $username = htmlspecialchars($_SESSION['username']);

        if ($hour >= 5 && $hour < 12) {
            // $greeting = "Morning, $username! Ready to start your day? ";
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
        <div class="card">
            <img src="../Assets/image/<?php echo htmlspecialchars($row['cover_path']); ?>" alt="Cover">
            <p class="title"><?php echo htmlspecialchars($row['title']); ?></p>
            <p class="artist"><?php echo htmlspecialchars($row['artist']); ?></p>

            <!-- Audio player (disembunyikan) -->
            <audio class="audio-player" src="../Admin/<?php echo htmlspecialchars($row['file_path']); ?>" preload="none"></audio>

            <!-- Tombol play/pause -->
            <button class="play-pause-btn">Play</button>

             <!-- Progress Bar -->
            <input type="range" class="progress-bar" value="0" min="0" step="1">

            <!-- Volume Control -->
            <input type="range" class="volume-slider" min="0" max="1" step="0.01" value="0.5" title="Volume">
        </div>

        <?php endwhile; ?>
    </div>
</section>

        <!-- Recommended -->
        <section class="content-box">
            <h2>🎵 Recommended for You</h2>
            <div class="card-container">
                <?php while ($row = mysqli_fetch_assoc($songResult)): ?>
                <div class="card">
                    <img src="../Assets/image/<?php echo htmlspecialchars($row['cover_path']); ?>" alt="Cover">
                    <p class="title"><?php echo htmlspecialchars($row['title']); ?></p>
                    <p class="artist"><?php echo htmlspecialchars($row['artist']); ?></p>
                </div>
                <?php endwhile; ?>
            </div>
        </section>


        <!-- Top Charts -->
        <section class="content-box">
            <h2>🔥 Top Charts</h2>
            <div class="card-container">
                <div class="card">
                    <img src="../Assets/image/cover1.png" alt="Album 4">
                    <p class="title">You're Mine</p>
                    <p class="artist">Vincentius Bryan</p>
                </div>
                <div class="card">
                    <img src="../Admin/uploads/covers/cover2.png" alt="Album 5">
                    <p class="title">Attention</p>
                    <p class="artist">Charlie Puth</p>
                </div>
                <div class="card">
                    <img src="../Admin/uploads/covers/cover3.jpg" alt="Album 6">
                    <p class="title">APT</p>
                    <p class="artist">ROSÉ & Bruno Mars</p>
                </div>

                <div class="card">
                    <img src="../Admin/uploads/covers/cover4.jpg" alt="Album 6">
                    <p class="title">BIRDS OF A FEATHER</p>
                    <p class="artist">Billie Eilish</p>
                </div>
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

cards.forEach((card, index) => {
    const audio = card.querySelector('.audio-player');
    const btn = card.querySelector('.play-pause-btn');
    const progressBar = card.querySelector('.progress-bar');
    const volumeSlider = card.querySelector('.volume-slider');

    // Set max progress bar setelah metadata loaded
    audio.addEventListener('loadedmetadata', () => {
        progressBar.max = Math.floor(audio.duration);
    });

    // Play/pause toggle
    btn.addEventListener('click', () => {
        if (audio.paused) {
            // Pause audio lain yang sedang dimainkan
            if (currentlyPlayingAudio && currentlyPlayingAudio !== audio) {
                currentlyPlayingAudio.pause();
                if (currentlyPlayingBtn) currentlyPlayingBtn.textContent = 'Play';
            }
            audio.play();
            btn.textContent = 'Pause';
            currentlyPlayingAudio = audio;
            currentlyPlayingBtn = btn;
        } else {
            audio.pause();
            btn.textContent = 'Play';
            currentlyPlayingAudio = null;
            currentlyPlayingBtn = null;
        }
    });

    // Update progress bar saat audio berjalan
    audio.addEventListener('timeupdate', () => {
        progressBar.value = Math.floor(audio.currentTime);
    });

    // Mengatur audio saat progress bar digeser
    progressBar.addEventListener('input', () => {
        audio.currentTime = progressBar.value;
    });

    // Atur volume berdasarkan slider
    volumeSlider.addEventListener('input', () => {
        audio.volume = volumeSlider.value;
    });

    // Saat lagu selesai, mainkan lagu selanjutnya
    audio.addEventListener('ended', () => {
        btn.textContent = 'Play';
        currentlyPlayingAudio = null;
        currentlyPlayingBtn = null;

        // Mainkan lagu berikutnya, jika ada
        const nextIndex = index + 1;
        if (nextIndex < cards.length) {
            const nextCard = cards[nextIndex];
            const nextAudio = nextCard.querySelector('.audio-player');
            const nextBtn = nextCard.querySelector('.play-pause-btn');

            nextAudio.play();
            nextBtn.textContent = 'Pause';

            currentlyPlayingAudio = nextAudio;
            currentlyPlayingBtn = nextBtn;
        }
    });
});
</script>


</body>
</html>