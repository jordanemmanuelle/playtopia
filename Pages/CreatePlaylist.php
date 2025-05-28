<?php
session_start();
include '../connection.php';

$userId = $_SESSION['id_user'];
$editMode = false;
$playlistData = [
    'playlist_name' => '',
    'description' => '',
    'cover_url' => '',
    'songs' => []
];
// ngedit
if (isset($_GET['id'])) {
    $editMode = true;
    $playlistId = intval($_GET['id']);
    $plRes = mysqli_query($connect, "SELECT * FROM playlists WHERE id_playlist=$playlistId AND id_user=$userId");
    if ($plRes && $row = mysqli_fetch_assoc($plRes)) {
        $playlistData['playlist_name'] = $row['playlist_name'];
        $playlistData['description'] = $row['description'];
        $playlistData['cover_url'] = $row['cover_url'];
        $songRes = mysqli_query($connect, "SELECT id_song FROM playlists_songs WHERE id_playlist=$playlistId");
        while ($s = mysqli_fetch_assoc($songRes)) {
            $playlistData['songs'][] = $s['id_song'];
        }
    } else {
        echo "<script>alert('Playlist tidak ditemukan!');window.location.href='../Pages/Playlist.php';</script>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['cover_file']) && $_FILES['cover_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads/covers/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileTmpPath = $_FILES['cover_file']['tmp_name'];
        $fileName = basename($_FILES['cover_file']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExt)) {
            $newFileName = uniqid('cover_') . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $coverUrl = "uploads/covers/" . $newFileName;
            } else {
                echo "<script>alert('Gagal meng-upload file cover.');</script>";
                exit;
            }
        } else {
            echo "<script>alert('Format file cover tidak didukung.');</script>";
            exit;
        }
    } else {
        // kalo gaada yang diupload, pake cover lama
        $coverUrl = $editMode ? $playlistData['cover_url'] : '';
    }

    $playlistName = $_POST['playlist_name'];
    $description = $_POST['description'];
    $selectedSongs = $_POST['songs'] ?? [];
    $createdAt = date('Y-m-d');

    if ($editMode) {
        // Update playlist
        $query = "UPDATE playlists SET playlist_name='$playlistName', description='$description', cover_url='$coverUrl' WHERE id_playlist=$playlistId AND id_user=$userId";
        $result = mysqli_query($connect, $query);

        mysqli_query($connect, "DELETE FROM playlists_songs WHERE id_playlist=$playlistId");
        if (!empty($selectedSongs)) {
            foreach ($selectedSongs as $songId) {
                $insertSong = "INSERT INTO playlists_songs (id_playlist, id_song) VALUES ($playlistId, $songId)";
                mysqli_query($connect, $insertSong);
            }
        }

        if ($result) {
            echo "<script>alert('Playlist berhasil diupdate!');window.location.href='../Pages/Playlist.php';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal mengupdate playlist.');</script>";
        }
    } else {
        $query = "INSERT INTO playlists (id_user, playlist_name, description, cover_url, created_at) 
                  VALUES ($userId, '$playlistName', '$description', '$coverUrl', '$createdAt')";
        $result = mysqli_query($connect, $query);

        if ($result) {
            $playlistId = mysqli_insert_id($connect);

            if (!empty($selectedSongs)) {
                foreach ($selectedSongs as $songId) {
                    $insertSong = "INSERT INTO playlists_songs (id_playlist, id_song) VALUES ($playlistId, $songId)";
                    mysqli_query($connect, $insertSong);
                }
            }

            echo "<script>alert('Playlist berhasil dibuat!');window.location.href='../Pages/Playlist.php';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal membuat playlist.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title><?= $editMode ? 'Edit Playlist' : 'Buat Playlist' ?></title>
    <link rel="stylesheet" href="../CSS/CreatePlaylist.css"/>
    <link rel="stylesheet" href="../CSS/homeCSS.css"/>
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

    <div class="form-container">
        <div class="form-header">
            <a href="../Pages/Playlist.php" class="back-button">← Back</a>
            <h1><?= $editMode ? 'Edit Playlist' : 'Buat Playlist Baru' ?></h1>
        </div>

        <form method="POST" action="" enctype="multipart/form-data">
            <label for="playlist_name">Nama Playlist:</label>
            <input type="text" id="playlist_name" name="playlist_name" required value="<?= htmlspecialchars($playlistData['playlist_name']) ?>" />


            <label for="description">Deskripsi:</label>
            <textarea id="description" name="description"><?= htmlspecialchars($playlistData['description']) ?></textarea>


            <label for="cover_file">Upload Cover Playlist:</label>
            <?php if ($editMode && !empty($playlistData['cover_url'])): ?>
            <p>Cover saat ini: <img src="<?= htmlspecialchars($playlistData['cover_url']) ?>" alt="Current Cover" style="height: 100px;"></p>
            <?php endif; ?>
            <input type="file" id="cover_file" name="cover_file" accept="image/*" />


            <label>Pilih Lagu:</label>
            <div class="song-list">
                <?php
                $songsQuery = "SELECT * FROM songs";
                $songsResult = mysqli_query($connect, $songsQuery);
                if ($songsResult && mysqli_num_rows($songsResult) > 0) {
                    while ($song = mysqli_fetch_assoc($songsResult)) {
                        $isChecked = in_array($song['id_song'], $playlistData['songs']) ? 'checked' : '';
                        echo "<label><input type='checkbox' name='songs[]' value='{$song['id_song']}' $isChecked /> {$song['title']} - {$song['artist']}</label><br />";
                    }

                } else {
                    echo "<p>Tidak ada lagu tersedia.</p>";
                }
                ?>
            </div>

            <input type="submit" value="<?= $editMode ? 'Update Playlist' : 'Buat Playlist' ?>" />


        </form>
    </div>
</body>

</html>