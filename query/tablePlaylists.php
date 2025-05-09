<?php
$connect = mysqli_connect("localhost", "root", "", "playtopia");

$sql = "
CREATE TABLE playlists (
    id_playlist int AUTO_INCREMENT PRIMARY KEY,
    id_user int,
    playlist_name VARCHAR(100),
    description TEXT,
    cover_url TEXT,
    created_at DATE NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
)";

if (mysqli_query($connect, $sql)) {
    echo "✅ Table 'playlists' created successfully.";
} else {
    echo "❌ Error creating table 'playlists': " . mysqli_error($connect);
}
?>
