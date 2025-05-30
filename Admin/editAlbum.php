<?php
include '../connection.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$album = null;

if ($id > 0) {
    // Proses update ketika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = mysqli_real_escape_string($connect, $_POST['name']);
        $artist = mysqli_real_escape_string($connect, $_POST['artist']);
        $release_year = intval($_POST['release_year']);

        $update_query = "UPDATE albums SET 
            name = '$name',
            artist = '$artist',
            release_year = $release_year
            WHERE id_album = $id";

        $update = mysqli_query($connect, $update_query);

        if ($update) {
            echo "<script>
                alert('Album berhasil diupdate!');
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

    // Ambil data album berdasarkan ID
    $result = mysqli_query($connect, "SELECT * FROM albums WHERE id_album = $id");

    if ($result) {
        $album = mysqli_fetch_assoc($result);

        if (!$album) {
            echo "<script>
                alert('Data album tidak ditemukan.');
                window.location.href = 'AllTabel.php';
            </script>";
            exit;
        }
    } else {
        echo "<script>
            alert('Terjadi kesalahan saat mengambil data album.');
            window.location.href = 'AllTabel.php';
        </script>";
        exit;
    }
} else {
    echo "<script>
        alert('ID album tidak valid.');
        window.location.href = 'AllTabel.php';
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../CSS/homeCSS.css">
        <link rel="stylesheet" href="../CSS/editSong.css">
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
                <a href="../Admin/AllTabel.php"><b>Table</b></a>
                <a href="../LoginRegister/Logout.php"><b>Logout</b></a>
            </nav>
        </header>

        <h1>Edit Album</h1>
        <div class="form-container">
            <form action="" method="post">
                <label>Title:</label><br>
                <input type="text" name="name" value="<?php echo htmlspecialchars($album['name']); ?>" required><br><br>

                <label>Artist:</label><br>
                <input type="text" name="artist" value="<?php echo htmlspecialchars($album['artist']); ?>" required><br><br>

                <label>Release Year:</label><br>
                <input type="number" name="release_year" value="<?php echo htmlspecialchars($album['release_year']); ?>" required><br><br>

                <input type="submit" value="Update Album">
            </form>
        </div>
    </body>
</html>
