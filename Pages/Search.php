<?php
session_start();
include '../connection.php';

$songQuery = "SELECT * FROM songs LIMIT 4";
$songResult = mysqli_query($connect, $songQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLAYTOPIA</title>
    <link rel="stylesheet" href="../CSS/searchCSS.css">
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
        <a href="#"><b>Home</b></a>
        <a href="#"><b>Search</b></a>
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
        <h1>Search Your Feelings</h1>
        <p class="tagline">Your Personal Music Playground</p>

        <?php 
        if (isset($_SESSION['username'])) {
            date_default_timezone_set('Asia/Jakarta');
            $hour = date('H');
            $username = htmlspecialchars($_SESSION['username']);

            if ($hour >= 12 && $hour < 17) {
                $greeting = "Hey $username, need a soundtrack for your afternoon?";
            } elseif ($hour >= 17 && $hour < 21) {
                $greeting = "Evening, $username! How was your day?";
            } else {
                $greeting = "Still up, $username? Let the music keep you company tonight";
            }

            echo "<p class='welcome-user'>$greeting</p>";
        }
        ?>

        <!-- Styled Search Input -->
        <form method="GET" action="Search.php" style="margin-top: 30px;">
            <input 
                type="text" 
                name="query" 
                id="searchInput"
                placeholder="Search songs, artists, albums..." 
                class="search-input" />
        </form>
    </section>

    <!-- Display Songs in Cards -->
    <section class="card-container" id="resultsContainer">
        <?php while ($row = mysqli_fetch_assoc($songResult)): ?>
            <div class="card">
                <img src="../Assets/image/<?php echo htmlspecialchars($row['cover_path']); ?>" alt="Song Image">
                <div class="title"><?php echo htmlspecialchars($row['title']); ?></div>
                <div class="artist"><?php echo htmlspecialchars($row['artist']); ?></div>
            </div>
        <?php endwhile; ?>
    </section>
</main>

<footer class="footer">
    <p>&copy; 2025 Playtopia. All rights reserved.</p>
</footer>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    const query = this.value;

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "../Ajax/searchHandler.php?query=" + encodeURIComponent(query), true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('resultsContainer').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
});
</script>
</body>
</html>
