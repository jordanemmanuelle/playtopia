<?php
include '../connection.php';

$id = intval($_GET['id']); // agar aman dari SQL Injection

$sql = "DELETE FROM songs WHERE id_song = $id";

if (mysqli_query($connect, $sql)) {
    header("Location: allTabel.php"); // arahkan ke halaman utama (sesuaikan nama file)
    exit;
} else {
    echo "Gagal menghapus lagu: " . mysqli_error($connect);
}

mysqli_close($connect);
?>
