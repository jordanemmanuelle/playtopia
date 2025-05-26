<?php
session_start();
include '../connection.php';
header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['success' => false, 'message' => 'You must login first']);
    exit;
}

$userId = $_SESSION['id_user'];

$query = "SELECT s.id_song, s.title, s.artist, s.cover_path, s.file_path, s.duration
          FROM songs s
          JOIN song_likes sl ON s.id_song = sl.id_song
          WHERE sl.id_user = $userId";

$result = mysqli_query($connect, $query);
$songs = [];

while ($row = mysqli_fetch_assoc($result)) {
    $songs[] = $row;
}

echo json_encode(['success' => true, 'songs' => $songs]);
?>