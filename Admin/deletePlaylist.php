<?php
include '../connection.php';

$id_playlist = intval($_GET['id']);

mysqli_begin_transaction($connect);

try {
    // Hapus dulu record yang tergantung di playlist_likes
    $sqlLikes = "DELETE FROM playlist_likes WHERE id_playlist = $id_playlist";
    mysqli_query($connect, $sqlLikes);

    // Baru hapus playlist
    $sqlPlaylist = "DELETE FROM playlists WHERE id_playlist = $id_playlist";
    mysqli_query($connect, $sqlPlaylist);

    mysqli_commit($connect);

    header("Location: allTabel.php"); // arahkan ke halaman utama
    exit;

} catch (Exception $e) {
    mysqli_rollback($connect);
    echo "Gagal menghapus playlist: " . $e->getMessage();
}

mysqli_close($connect);
?>
