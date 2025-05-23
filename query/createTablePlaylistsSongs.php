<?php
session_start();
include '../connection.php';

$userId = $_SESSION['id_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $playlistName = $_POST['playlist_name'];
    $description = $_POST['description'];
    $coverUrl = $_POST['cover_url'];
    $selectedSongs = $_POST['songs']; // array lagu terpilih

    $createdAt = date('Y-m-d');

    // Insert playlist
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

        header("Location: Playlists.php");
        exit;
    } else {
        echo "Gagal membuat playlist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Create Playlist</title>
    <link rel="stylesheet" href="../CSS/Playlist.css" />
</head>
<body>
<div class="main-content">
    <h2>Buat Playlist Baru</h2>

    <form method="POST" action="">
        <label for="playlist_name">Nama Playlist:</label><br />
        <input type="text" id="playlist_name" name="playlist_name" required /><br /><br />

        <label for="description">Deskripsi:</label><br />
        <textarea id="description" name="description"></textarea><br /><br />

        <label for="cover_url">URL Cover Image:</label><br />
        <input type="text" id="cover_url" name="cover_url" /><br /><br />

        <label>Pilih Lagu:</label><br />
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
        </div><br />

        <button type="submit">Buat Playlist</button>
    </form>
</div>
</body>
</html>
