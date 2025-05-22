<?php
    $connect = mysqli_connect("localhost", "root", "");
    if (mysqli_connect_errno()) {
        echo ("Failed to connect to MySQL: ") . mysqli_connect_error();
    }

    $sql = "CREATE DATABASE playtopia";
    if (mysqli_query($connect, $sql)) {
        echo ("Database created successfully");
    } else {
        echo ("Error creating database: " . mysqli_error($connect));
    }
    mysqli_close($connect);
?>