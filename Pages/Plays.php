<?php
session_start();

include '../connection.php';

if (isset($_POST['song_id']) && isset($_SESSION['id_user'])) {
    $song_id = $_POST['song_id'];

    $query = "UPDATE songs SET plays = plays + 1 WHERE id_song = '$song_id'";
    $result = mysqli_query($connect, $query);

    if ($result) {
        $getPlays = "SELECT plays FROM songs WHERE id_song = '$song_id'";
        $playsResult = mysqli_query($connect, $getPlays);
        $row = mysqli_fetch_assoc($playsResult);

        echo json_encode(['success' => true, 'plays' => $row['plays']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed.']);
    }
} else {
    // Tidak ada sesi login atau song_id, maka plays tidak ditambahkan
    echo json_encode(['success' => false, 'message' => 'Unauthorized or missing song_id.']);
}
?>
