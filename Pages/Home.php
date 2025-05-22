<?php
    session_start();
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
            <img src="../image/LogoPlaytopia1.png">
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
            <h1>Welcome to Playtopia</h1>
            <p>Your personalized music experience starts here.</p>
        </section>

        <!-- Recommended -->
        <section class="content-box">
            <h2>🎵 Recommended for You</h2>
            <div class="card-container">
                <div class="card">
                    <img src="../image/album1.jpg" alt="Album 1">
                    <p class="title">Midnight Memories</p>
                    <p class="artist">One Direction</p>
                </div>
                <div class="card">
                    <img src="../image/album2.jpg" alt="Album 2">
                    <p class="title">Divide</p>
                    <p class="artist">Ed Sheeran</p>
                </div>
                <div class="card">
                    <img src="../image/album3.jpg" alt="Album 3">
                    <p class="title">Future Nostalgia</p>
                    <p class="artist">Dua Lipa</p>
                </div>
            </div>
        </section>

        <!-- Top Charts -->
        <section class="content-box">
            <h2>🔥 Top Charts</h2>
            <div class="card-container">
                <div class="card">
                    <img src="../image/album4.jpg" alt="Album 4">
                    <p class="title">After Hours</p>
                    <p class="artist">The Weeknd</p>
                </div>
                <div class="card">
                    <img src="../image/album5.jpg" alt="Album 5">
                    <p class="title">Folklore</p>
                    <p class="artist">Taylor Swift</p>
                </div>
                <div class="card">
                    <img src="../image/album6.jpg" alt="Album 6">
                    <p class="title">Justice</p>
                    <p class="artist">Justin Bieber</p>
                </div>
            </div>
        </section>

        <!-- Genres -->
        <section class="content-box">
            <h2>🎧 Genres</h2>
            <div class="card-container">
                <div class="card">
                    <img src="../image/genre-pop.jpg" alt="Pop">
                    <p class="title">Pop</p>
                </div>
                <div class="card">
                    <img src="../image/genre-rock.jpg" alt="Rock">
                    <p class="title">Rock</p>
                </div>
                    <div class="card">
                    <img src="../image/genre-hiphop.jpg" alt="Hip-Hop">
                    <p class="title">Hip-Hop</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2025 Playtopia. All rights reserved.</p>
    </footer>

</body>
</html>