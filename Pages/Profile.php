<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../LoginRegister/FormLogin.html");
    exit;
}

$id_user = $_SESSION['id_user'];
$username = $_SESSION['username'];

// Ambil data user dari database
$query = "SELECT email, username FROM users WHERE id_user = '$id_user'";
$result = mysqli_query($connect, $query);
$user = mysqli_fetch_assoc($result);

// Proses update username
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_username'])) {
    $newUsername = htmlspecialchars(trim($_POST['new_username']));
    if (!empty($newUsername)) {
        $updateQuery = "UPDATE users SET username = '$newUsername' WHERE id_user = '$id_user'";
        if (mysqli_query($connect, $updateQuery)) {
            $_SESSION['username'] = $newUsername;
            echo "<script>
                alert('Username updated successfully!');
                window.location.href = '../Pages/Home.php';
            </script>";
            exit;
        } else {
            echo "<script>
                alert('Error updating username.');
                window.location.href = 'Profile.php';
            </script>";
            exit;
        }
    } else {
        echo "<script>
            alert('Username cannot be empty.');
            window.location.href = 'Profile.php';
        </script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Profile - Playtopia</title>
        <link rel="stylesheet" href="../CSS/Profile.css">
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

        <div class="profile-container">
            <h2>Your Profile</h2>
            <form method="POST">
                <label for="email">Email:</label>
                <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>

                <label for="username">Username:</label>
                <input type="text" name="new_username" value="<?= htmlspecialchars($user['username']) ?>" required>

                <button type="submit">Update Username</button>
            </form>
            <a href="../Pages/Home.php" class="back-button">← Back to Home</a>
            <a href="../LoginRegister/FormForgotPassword.html">Change Password</a>
        </div>
    </body>
</html>
