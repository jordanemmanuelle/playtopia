<?php
$connect = mysqli_connect("localhost", "root", "", "playtopia");

// Langkah 1: Buat tabel songs
$sql = "CREATE TABLE IF NOT EXISTS songs (
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
    echo "✅ Table 'songs' created successfully.<br>";

    $alter1 = "ALTER TABLE songs ADD COLUMN plays INT DEFAULT 0";
    mysqli_query($connect, $alter1);

    $alter2 = "ALTER TABLE songs 
        ADD COLUMN id_album INT,
        ADD FOREIGN KEY (id_album) REFERENCES albums(id_album) ON DELETE SET NULL";
    mysqli_query($connect, $alter2);

    echo "✅ Kolom 'plays' dan 'id_album' ditambahkan.";
} else {
    echo "❌ Error creating table 'songs': " . mysqli_error($connect);
}
?>
