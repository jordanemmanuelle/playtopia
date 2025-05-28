<?php
include '../connection.php';

$id = intval($_GET['id']); 

$sql = "DELETE FROM songs WHERE id_song = $id";

if (mysqli_query($connect, $sql)) {
    header("Location: allTabel.php"); 
    exit;
} else {
    echo "Gagal menghapus lagu: " . mysqli_error($connect);
}

mysqli_close($connect);
?>
