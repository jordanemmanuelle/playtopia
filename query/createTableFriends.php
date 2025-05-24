<?php
$connect = mysqli_connect("localhost", "root", "", "playtopia");

if (mysqli_connect_errno()) {
    echo ("Failed to connect to MySQL: " . mysqli_connect_error());
}

$sql = "CREATE TABLE friends (
    id_user1 int not null,
    id_user2 int not null,
    status ENUM('pending', 'accepted') DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_user1, id_user2),
    FOREIGN KEY (id_user1) REFERENCES users(id_user),
    FOREIGN KEY (id_user2) REFERENCES users(id_user)
)";


if (mysqli_query($connect, $sql)) {
    echo ("Table 'friends' created successfully");
} else {
    echo ("Error creating table: " . mysqli_error($connect));
}
?>
