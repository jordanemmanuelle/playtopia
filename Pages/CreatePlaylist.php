<?php
session_start();
include '../connection.php';

$userId = $_SESSION['id_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle upload cover file
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
        $coverUrl = ''; // optional jika tidak upload cover
    }

    $playlistName = $_POST['playlist_name'];
    $description = $_POST['description'];
    $selectedSongs = $_POST['songs'] ?? [];

    $createdAt = date('Y-m-d');

    // Insert playlist tanpa prepared statement, langsung pakai variabel (sesuai permintaan)
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Create Playlist</title>
    <link rel="stylesheet" href="../CSS/CreatePlaylist.css" />
</head>
<body>
    <div class="playtopia-header">
        <div class="logo">Playtopia</div>
    </div>

    <div class="form-container">
        <div class="form-header">
            <a href="../Pages/Playlist.php" class="back-button">← Back</a>
            <h1>Buat Playlist Baru</h1>
        </div>

        <form method="POST" action="" enctype="multipart/form-data">
            <label for="playlist_name">Nama Playlist:</label>
            <input type="text" id="playlist_name" name="playlist_name" required />

            <label for="description">Deskripsi:</label>
            <textarea id="description" name="description"></textarea>

            <label for="cover_file">Upload Cover Playlist:</label>
            <input type="file" id="cover_file" name="cover_file" accept="image/*" />

            <label>Pilih Lagu:</label>
            <div class="song-list">
                <?php
                $songsQuery = "SELECT * FROM songs";
                $songsResult = mysqli_query($connect, $songsQuery);
                if ($songsResult && mysqli_num_rows($songsResult) > 0) {
                    while ($song = mysqli_fetch_assoc($songsResult)) {
                        echo "<label><input type='checkbox' name='songs[]' value='{$song['id_song']}' /> {$song['title']} - {$song['artist']}</label><br />";
                    }
                } else {
                    echo "<p>Tidak ada lagu tersedia.</p>";
                }
                ?>
            </div>

            <input type="submit" value="Buat Playlist" />
            
        </form>
    </div>
</body>
</html>
