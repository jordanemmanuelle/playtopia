<?php
$connect = mysqli_connect("localhost", "root", "", "playtopia");

$sql = "CREATE TABLE playlists (
    id_playlist INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    playlist_name VARCHAR(100) NOT NULL,
    description TEXT,
    cover_url VARCHAR(255),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
)";

if (mysqli_query($connect, $sql)) {
    echo "✅ Table 'playlists' created successfully.";
} else {
    echo "❌ Error creating table 'playlists': " . mysqli_error($connect);
}
?>
