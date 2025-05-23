<?php
$connect = mysqli_connect("localhost", "root", "", "playtopia");

$sql = "CREATE TABLE songs (
    id_song INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    artist VARCHAR(100) NOT NULL,
    album VARCHAR(100),
    genre VARCHAR(50),
    release_year YEAR,
    duration INT,
    file_path VARCHAR(255),
    cover_path VARCHAR(255)
)";

if (mysqli_query($connect, $sql)) {
    echo "✅ Table 'songs' created successfully.";
} else {
    echo "❌ Error creating table 'songs': " . mysqli_error($connect);
}
?>
