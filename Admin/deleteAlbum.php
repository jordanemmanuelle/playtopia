<?php
include '../connection.php';

$id_album = intval($_GET['id']); // ambil ID album dari URL

mysqli_begin_transaction($connect);

try {
    $sqlAlbum = "DELETE FROM albums WHERE id_album = $id_album";
    mysqli_query($connect, $sqlAlbum);
    mysqli_commit($connect);
    header("Location: allTabel.php");
    exit;

} catch (Exception $e) {
    mysqli_rollback($connect);
    echo "Gagal menghapus album: " . $e->getMessage();
}

mysqli_close($connect);
?>