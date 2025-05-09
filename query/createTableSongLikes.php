<?php
$connect = mysqli_connect("localhost", "root", "", "playtopia");

$sql = "
CREATE TABLE song_likes (
    id_user VARCHAR(10),
    id_song VARCHAR(10),
    liked_at DATE NOT NULL DEFAULT CURRENT_DATE,
    PRIMARY KEY (id_user, id_song),
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_song) REFERENCES songs(id_song)
)";

if (mysqli_query($connect, $sql)) {
    echo "✅ Table 'song_likes' created successfully.";
} else {
    echo "❌ Error creating table 'song_likes': " . mysqli_error($connect);
}
?>
