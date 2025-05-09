<?php
$connect = mysqli_connect("localhost", "root", "", "playtopia");

$sql = "
CREATE TABLE playlist_likes (
    id_user int,
    id_playlist int,
    liked_at DATE NOT NULL DEFAULT CURRENT_DATE,
    PRIMARY KEY (id_user, id_playlist),
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_playlist) REFERENCES playlists(id_playlist)
)";

if (mysqli_query($connect, $sql)) {
    echo "✅ Table 'playlist_likes' created successfully.";
} else {
    echo "❌ Error creating table 'playlist_likes': " . mysqli_error($connect);
}
?>
