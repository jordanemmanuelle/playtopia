<?php
$connect = mysqli_connect("localhost", "root", "", "playtopia");

$sql = "CREATE TABLE IF NOT EXISTS albums (
    id_album INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    artist VARCHAR(100) NOT NULL,
    genre VARCHAR(50),
    release_year YEAR,
    cover_path VARCHAR(255)
)";

if (mysqli_query($connect, $sql)) {
    echo "✅ Table 'albums' created successfully.";
} else {
    echo "❌ Error creating table 'albums': " . mysqli_error($connect);
}
?>
