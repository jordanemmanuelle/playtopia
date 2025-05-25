<?php
include '../connection.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$song = null;

if ($id > 0) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $artist = $_POST['artist'];
        $album = $_POST['album'];
        $genre = $_POST['genre'];
        $release_year = $_POST['release_year'];
        $duration = $_POST['duration'];
        $plays = $_POST['plays'];

        // Ambil file lama
        $filePath = $_POST['old_file_path'];
        if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] == 0) {
            $fileName = basename($_FILES['file_path']['name']);
            $uploadDir = __DIR__ . '/../Admin/';
            // Upload file baru
            if (move_uploaded_file($_FILES['file_path']['tmp_name'], $uploadDir . $fileName)) {
                $filePath = $fileName;  // simpan nama file ke DB
            }
        }

        $coverPath = $_POST['old_cover_path'];
        if (isset($_FILES['cover_path']) && $_FILES['cover_path']['error'] == 0) {
            $coverName = basename($_FILES['cover_path']['name']);
            $uploadDir = __DIR__ . '/../Assets/image/';
            // Upload file baru
            if (move_uploaded_file($_FILES['cover_path']['tmp_name'], $uploadDir . $coverName)) {
                $coverPath = $coverName;
            }
        }

        // Update data ke DB
        $update = mysqli_query($connect, "UPDATE songs SET 
            title = '$title',
            artist = '$artist',
            album = '$album',
            genre = '$genre',
            release_year = '$release_year',
            duration = '$duration',
            file_path = '$filePath',
            cover_path = '$coverPath',
            plays = '$plays'
            WHERE id_song = $id
        ");

        if ($update) {
            echo "<script>
                alert('Data berhasil diupdate!');
                window.location.href = 'AllTabel.php';
            </script>";
            exit;
        } else {
            echo "<script>
                alert('Gagal update: " . mysqli_error($connect) . "');
                window.history.back();
            </script>";
            exit;
        }
    }

    // Ambil data terbaru dari DB
    $result = mysqli_query($connect, "SELECT * FROM songs WHERE id_song = $id");
    $song = mysqli_fetch_assoc($result);

    if (!$song) {
        echo "Data lagu tidak ditemukan.";
        exit;
    }
} else {
    echo "ID tidak valid.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Song</title>
</head>
<body>
    <h1>Edit Song</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($song['title']); ?>" required><br><br>

        <label>Artist:</label><br>
        <input type="text" name="artist" value="<?php echo htmlspecialchars($song['artist']); ?>" required><br><br>

        <label>Album:</label><br>
        <input type="text" name="album" value="<?php echo htmlspecialchars($song['album']); ?>" required><br><br>

        <label>Genre:</label><br>
        <input type="text" name="genre" value="<?php echo htmlspecialchars($song['genre']); ?>" required><br><br>

        <label>Release Year:</label><br>
        <input type="number" name="release_year" value="<?php echo htmlspecialchars($song['release_year']); ?>" required><br><br>

        <label>Duration:</label><br>
        <input type="text" name="duration" value="<?php echo htmlspecialchars($song['duration']); ?>" required><br><br>

        <label>File Lagu (MP3):</label><br>
        <input type="file" name="file_path"><br>
        <small>File sebelumnya: <?php echo htmlspecialchars($song['file_path']); ?></small><br><br>
        <input type="hidden" name="old_file_path" value="<?php echo htmlspecialchars($song['file_path']); ?>">

        <label>Cover (Gambar):</label><br>
        <input type="file" name="cover_path"><br>
        <small>File sebelumnya: <?php echo htmlspecialchars($song['cover_path']); ?></small><br><br>
        <input type="hidden" name="old_cover_path" value="<?php echo htmlspecialchars($song['cover_path']); ?>">

        <label>Plays:</label><br>
        <input type="number" name="plays" value="<?php echo htmlspecialchars($song['plays']); ?>" required><br><br>

        <input type="submit" value="Update Song">
    </form>
</body>
</html>
