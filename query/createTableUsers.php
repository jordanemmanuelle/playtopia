<?php
    $connect = mysqli_connect("localhost", "root", "", "playtopia");
    
    if (mysqli_connect_errno()) {
        echo ("Failed to connect to MySQL: " . mysqli_connect_error());
    }

    $sql = "CREATE TABLE users (
    id_user INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id_user), 
    username varchar(26) NOT NULL, 
    email varchar(100) not null unique check(email LIKE'%@%'),
    password varchar(255) not null,
    created_at DATE NOT NULL DEFAULT CURRENT_DATE,
    user_type ENUM('admin', 'user') DEFAULT 'user'
    )";

    if (mysqli_query($connect, $sql)) {
        echo ("Table 'users' created successfully");
    } else {
        echo ("Error creating table");
    }
?>