<?php
$connect = mysqli_connect("localhost", "root", "", "playtopia");

$sql = "
CREATE TABLE playlist_shares (
    id_shares VARCHAR(10) PRIMARY KEY,
    id_sender VARCHAR(10),
    id_receiver VARCHAR(10),
    id_playlist VARCHAR(10),
    share_at DATE NOT NULL DEFAULT CURRENT_DATE,
    FOREIGN KEY (id_sender) REFERENCES users(id_user),
    FOREIGN KEY (id_receiver) REFERENCES users(id_user),
    FOREIGN KEY (id_playlist) REFERENCES playlists(id_playlist)
)";

if (mysqli_query($connect, $sql)) {
    echo "✅ Table 'playlist_shares' created successfully.";
} else {
    echo "❌ Error creating table 'playlist_shares': " . mysqli_error($connect);
}
?>
