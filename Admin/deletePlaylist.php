<?php
include '../connection.php';

$id_playlist = intval($_GET['id']);

mysqli_begin_transaction($connect);

try {
    $sqlLikes = "DELETE FROM playlist_likes WHERE id_playlist = $id_playlist";
    mysqli_query($connect, $sqlLikes);

    $sqlPlaylist = "DELETE FROM playlists WHERE id_playlist = $id_playlist";
    mysqli_query($connect, $sqlPlaylist);

    mysqli_commit($connect);

    header("Location: allTabel.php"); 
    exit;

} catch (Exception $e) {
    mysqli_rollback($connect);
    echo "Gagal menghapus playlist: " . $e->getMessage();
}

mysqli_close($connect);
?>
