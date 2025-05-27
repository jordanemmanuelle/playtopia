<?php
include '../connection.php';

// Get all songs from database
$sql = "SELECT id_song, title, artist, album, genre, release_year, duration, file_path, cover_path, plays FROM songs ORDER BY title ASC";
$result = mysqli_query($connect, $sql);

if (mysqli_num_rows($result) > 0) {
    echo '<div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="song-checkbox">';
        echo '<label>';
        echo '<input type="checkbox" name="existing_songs[]" value="' . $row['id_song'] . '">';
        echo ' <strong>' . htmlspecialchars($row['title']) . '</strong>';
        echo ' - ' . htmlspecialchars($row['artist']);
        
        if (!empty($row['album'])) {
            echo ' | Album: ' . htmlspecialchars($row['album']);
        }
        
        if (!empty($row['genre'])) {
            echo ' | Genre: ' . htmlspecialchars($row['genre']);
        }
        
        if (!empty($row['release_year'])) {
            echo ' | Year: ' . $row['release_year'];
        }
        
        if (isset($row['plays']) && $row['plays'] > 0) {
            echo ' | Plays: ' . $row['plays'];
        }
        
        echo '</label>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p><em>No songs found in database.</em></p>';
}

mysqli_close($connect);
?>