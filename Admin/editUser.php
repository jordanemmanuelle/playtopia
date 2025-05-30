<?php
include '../connection.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user = null;

if ($id > 0) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = mysqli_real_escape_string($connect, $_POST['username']);
        $email = mysqli_real_escape_string($connect, $_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Ambil password lama
        $old_result = mysqli_query($connect, "SELECT password FROM users WHERE id_user = $id");
        $old_data = mysqli_fetch_assoc($old_result);
        $old_password = $old_data['password'];

        // Jika password dikirim, proses ganti password
        if (!empty($password)) {
            if ($password !== $confirm_password) {
                echo "<script>
                    alert('Password dan konfirmasi password tidak sama!');
                    window.history.back();
                </script>";
                exit;
            }

            $hashed_new_password = md5($password);

            if ($hashed_new_password === $old_password) {
                echo "<script>
                    alert('Password baru tidak boleh sama dengan password lama!');
                    window.history.back();
                </script>";
                exit;
            }

            $update_query = "UPDATE users SET 
                username = '$username',
                email = '$email',
                password = '$hashed_new_password'
                WHERE id_user = $id";
        } else {
            // Jika password tidak diubah
            $update_query = "UPDATE users SET 
                username = '$username',
                email = '$email'
                WHERE id_user = $id";
        }

        $update = mysqli_query($connect, $update_query);

        if ($update) {
            echo "<script>
                alert('Data user berhasil diupdate!');
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

    $result = mysqli_query($connect, "SELECT * FROM users WHERE id_user = $id");
    if ($result) {
        $user = mysqli_fetch_assoc($result);
        if (!$user) {
            echo "<script>
                alert('User tidak ditemukan.');
                window.location.href = 'AllTabel.php';
            </script>";
            exit;
        }
    } else {
        echo "<script>
            alert('Terjadi kesalahan saat mengambil data.');
            window.location.href = 'AllTabel.php';
        </script>";
        exit;
    }
} else {
    echo "<script>
        alert('ID user tidak valid.');
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

        <h1>Edit User</h1>
        <div class="form-container">
            <form action="" method="post">
                <label>Username:</label><br>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br><br>

                <label>Email:</label><br>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>

                <label>Password Baru (kosongkan jika tidak ingin mengubah):</label><br>
                <input type="password" name="password"><br><br>

                <label>Konfirmasi Password Baru:</label><br>
                <input type="password" name="confirm_password"><br><br>

                <input type="submit" value="Update User">
            </form>
        </div>
    </body>
</html>
