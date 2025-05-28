<?php
include '../connection.php';

$id_user = intval($_GET['id']);

mysqli_begin_transaction($connect);

try {
    $sqlFriends = "DELETE FROM friends WHERE id_user1 = $id_user OR id_user2 = $id_user";
    mysqli_query($connect, $sqlFriends);

    $sqlUser = "DELETE FROM users WHERE id_user = $id_user";
    mysqli_query($connect, $sqlUser);

    mysqli_commit($connect);

    header("Location: allTabel.php"); 
    exit;

} catch (Exception $e) {
    mysqli_rollback($connect);
    echo "Gagal menghapus user: " . $e->getMessage();
}

mysqli_close($connect);
?>
