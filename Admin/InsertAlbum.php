<?php
include '../connection.php';

if (!$connect) {
    die("❌ Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $album = $_POST['album'];
    $artist = $_POST['artist'];
    $genre = $_POST['genre'];
    $release_year = (int)$_POST['release_year'];

    $coverName = $_FILES['cover']['name'];
    $coverTmp = $_FILES['cover']['tmp_name'];
    $coverFileName = basename($coverName);
    $coverPath = '../Assets/image/' . $coverFileName;

    if (!move_uploaded_file($coverTmp, $coverPath)) {
        die("❌ Gagal upload cover.");
    }

    $stmtAlbum = mysqli_prepare($connect, "INSERT INTO albums (name, artist, genre, release_year, cover_path) VALUES (?, ?, ?, ?, ?)");
    if (!$stmtAlbum) {
        die("❌ Prepare failed: " . mysqli_error($connect));
    }

    mysqli_stmt_bind_param($stmtAlbum, "sssis", $album, $artist, $genre, $release_year, $coverFileName);
    mysqli_stmt_execute($stmtAlbum);
    $id_album = mysqli_insert_id($connect);

    for ($i = 0; $i < 4; $i++) {
        $title = $_POST['title' . $i];
        $duration = $_POST['duration' . $i];
        $fileName = basename($_FILES['file' . $i]['name']);
        $fileTmp = $_FILES['file' . $i]['tmp_name'];
        $filePath = '../Assets/song/' . $fileName;

        if (!move_uploaded_file($fileTmp, $filePath)) {
            echo "❌ Gagal upload lagu ke-$i ($title)<br>";
            continue;
        }

        $stmtSong = mysqli_prepare($connect, "INSERT INTO songs (title, duration, artist, album, genre, release_year, file_path, cover_path, id_album) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmtSong) {
            die("❌ Prepare failed (song): " . mysqli_error($connect));
        }

        mysqli_stmt_bind_param($stmtSong, "ssssssssi", $title, $duration, $artist, $album, $genre, $release_year, $fileName, $coverFileName, $id_album);
        mysqli_stmt_execute($stmtSong);
    }


    echo "✅ Album dan keempat lagu berhasil diupload.";
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Insert Album</title>
    <link rel="stylesheet" href="../CSS/homeCSS.css">
    <link rel="stylesheet" href="../CSS/InsertAlbum.css">
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
                <h2>Admin Panel</h2>
                <button class="close-btn">&times;</button>
            </div>
            <ul>
                <li><a href="AddSongs.php">Tambah Lagu</a></li>
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
            <a href="../Pages/AdminMenu.php"><b>Dashboard</b></a>
            <a href="../LoginRegister/Logout.php"><b>Logout</b></a>
        </nav>
    </header>
    
    <h1>🎵 Insert New Album (4 Songs)</h1>
    <form action="InsertAlbum.php" method="post" enctype="multipart/form-data">
        <h2>Album Information</h2>
        <label>Album Name:</label><br>
        <input type="text" name="album" required><br><br>

        <label>Artist:</label><br>
        <input type="text" name="artist" required><br><br>

        <label>Genre:</label><br>
        <input type="text" name="genre" required><br><br>

        <label>Release Year:</label><br>
        <input type="number" name="release_year" min="1900" max="2100" required><br><br>

        <label>Album Cover Image:</label><br>
        <input type="file" name="cover" accept="image/*" required><br><br>

        <?php for ($i = 0; $i < 4; $i++): ?>
            <fieldset style="margin-bottom:20px;">
                <legend><strong>Song <?php echo $i + 1; ?></strong></legend>
                <label>Title:</label><br>
                <input type="text" name="title<?php echo $i; ?>" required><br>

                <label>Audio File:</label><br>
                <input type="file" name="file<?php echo $i; ?>" accept="audio/*" required><br>

                <label>Duration (second):</label><br>
                <input type="text" name="duration<?php echo $i; ?>" placeholder="ex: 231" required><br>
            </fieldset>
        <?php endfor; ?>

        <input type="submit" value="Insert 4 Songs">
    </form>
</body>
</html>
