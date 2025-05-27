<?php
include '../connection.php';

$id_user = intval($_GET['id']);

mysqli_begin_transaction($connect);

try {
    // Hapus dulu record yang terkait di tabel friends (baik id_user1 atau id_user2)
    $sqlFriends = "DELETE FROM friends WHERE id_user1 = $id_user OR id_user2 = $id_user";
    mysqli_query($connect, $sqlFriends);

    // Baru hapus user
    $sqlUser = "DELETE FROM users WHERE id_user = $id_user";
    mysqli_query($connect, $sqlUser);

    mysqli_commit($connect);

    header("Location: allTabel.php"); // arahkan ke halaman utama
    exit;

} catch (Exception $e) {
    mysqli_rollback($connect);
    echo "Gagal menghapus user: " . $e->getMessage();
}

mysqli_close($connect);
?>
