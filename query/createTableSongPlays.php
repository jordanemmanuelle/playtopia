<?php
    $connect = mysqli_connect("localhost", "root", "", "playtopia");
    
    if (mysqli_connect_errno()) {
        echo ("Failed to connect to MySQL: " . mysqli_connect_error());
    }

    $sql = "CREATE TABLE songplays (
    id_play INT AUTO_INCREMENT PRIMARY KEY,
    id_song INT NOT NULL,
    id_user INT,
    played_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_song) REFERENCES songs(id_song) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE SET NULL
    )";

    if (mysqli_query($connect, $sql)) {
        echo ("Table 'songplays' created successfully");
    } else {
        echo ("Error creating table");
    }
?>