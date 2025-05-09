<?php
$connect = mysqli_connect("localhost", "root", "", "playtopia");

$sql = "
CREATE TABLE playlists_songs (
    id_playlist VARCHAR(10),
    id_song VARCHAR(10),
    PRIMARY KEY (id_playlist, id_song),
    FOREIGN KEY (id_playlist) REFERENCES playlists(id_playlist),
    FOREIGN KEY (id_song) REFERENCES songs(id_song)
)";

if (mysqli_query($connect, $sql)) {
    echo "✅ Table 'playlists_songs' created successfully.";
} else {
    echo "❌ Error creating table 'playlists_songs': " . mysqli_error($connect);
}
?>
