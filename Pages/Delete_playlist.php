<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../LoginRegister/FormLogin.html");
    exit;
}

$userId = $_SESSION['id_user'];
$playlistId = $_GET['id'] ?? null;

if ($playlistId) {
    // Delete songs from junction table first
    mysqli_query($connect, "DELETE FROM playlists_songs WHERE id_playlist = $playlistId");
    
    // Then delete the playlist itself
    mysqli_query($connect, "DELETE FROM playlists WHERE id_playlist = $playlistId AND id_user = $userId");
}

header("Location: Playlist.php");
exit;
?>