<?php
include '../connection.php';

// Create albums table
$sql_albums = "CREATE TABLE IF NOT EXISTS albums (
    id_album INT AUTO_INCREMENT PRIMARY KEY,
    album_name VARCHAR(100) NOT NULL,
    artist VARCHAR(100) NOT NULL,
    genre VARCHAR(50),
    release_year YEAR,
    cover_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$sql_add_id_album = "ALTER TABLE songs ADD COLUMN id_album INT";

if (mysqli_query($connect, $sql_albums)) {
    echo "✅ Table 'albums' created successfully.<br>";
} else {
    echo "❌ Error creating table 'albums': " . mysqli_error($connect) . "<br>";
}

if (mysqli_query($connect, $sql_add_id_album)) {
    echo "✅ Column 'id_album' added to songs table successfully.<br>";
} else {
    echo "ℹ️ Column 'id_album' might already exist: " . mysqli_error($connect) . "<br>";
}

mysqli_close($connect);
?>