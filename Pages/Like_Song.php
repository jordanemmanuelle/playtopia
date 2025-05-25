<?php
session_start();
include '../connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['success' => false, 'message' => 'You must login first']);
    exit;
}

$userId = $_SESSION['id_user'];
$songId = intval($_POST['song_id'] ?? 0);
$liked = intval($_POST['liked'] ?? 0);

if (!$songId) {
    echo json_encode(['success' => false, 'message' => 'Invalid song id']);
    exit;
}

if ($liked) {
    $query = "INSERT IGNORE INTO song_likes (id_user, id_song) VALUES ($userId, $songId)";
} else {
    $query = "DELETE FROM song_likes WHERE id_user = $userId AND id_song = $songId";
}

if (mysqli_query($connect, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
