<?php
    include "FormRegister.html";

    $connect = mysqli_connect("localhost", "root", "", "playtopia");

    if (mysqli_connect_errno()) {
        echo (mysqli_connect_error());
    }

    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = md5($password);

    $sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $email, $username, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
        echo ("Daftar berhasil");
    } else {
        echo ("Error: " . mysqli_error($connect));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connect);
    
?>