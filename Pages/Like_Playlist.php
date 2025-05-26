<?php
session_start();
include '../connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['success' => false, 'message' => 'You must login first']);
    exit;
}

$userId = $_SESSION['id_user'];
$playlistId = intval($_POST['playlist_id'] ?? 0);
$liked = intval($_POST['liked'] ?? 0);

if (!$playlistId) {
    echo json_encode(['success' => false, 'message' => 'Invalid playlist id']);
    exit;
}

if ($liked) {
    $query = "INSERT IGNORE INTO playlist_likes (id_user, id_playlist) VALUES ($userId, $playlistId)";
} else {
    $query = "DELETE FROM playlist_likes WHERE id_user = $userId AND id_playlist = $playlistId";
}

if (mysqli_query($connect, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
