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
                    <img src="../Assets/image/album1.jpg" alt="Album 1">
                    <p class="title">SOS</p>
                    <p class="artist">SZA</p>
                </div>
                <div class="card">
                    <img src="../Assets/image/album2.webp" alt="Album 2">
                    <p class="title">Voicenotes</p>
                    <p class="artist">Charlie Puth</p>
                </div>
                <div class="card">
                    <img src="../Assets/image/album3.avif" alt="Album 3">
                    <p class="title">Devide</p>
                    <p class="artist">Ed Sheeran</p>
                </div>

                <div class="card">
                    <img src="../Assets/image/album4.jpg" alt="Album 4">
                    <p class="title">Justice</p>
                    <p class="artist">Justin Bieber</p>
                </div>
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

</body>
</html>