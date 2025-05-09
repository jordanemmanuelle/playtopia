<?php
    include "FormLogin.html";

    session_start();

    $connect = mysqli_connect("localhost", "root", "", "playtopia");

    if (mysqli_connect_errno()) {
        echo (mysqli_connect_error());
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashed_password = md5($password);

    $sql = "SELECT * FROM users WHERE email ='$email' AND password ='$hashed_password'";
    $result = mysqli_query($connect, $sql);

    if (mysqli_num_rows($result) === 1) { // cek ada brp data
        $logged_in_user = mysqli_fetch_assoc($result); // kalau cuma 1, datanya di-get

        //save ke session
        $_SESSION['id_user'] = $logged_in_user['id_user'];
        $_SESSION['username'] = $logged_in_user['username'];

        header("Location: ../Pages/Home.php");
        exit();
    } else {
        echo ("Email atau password salah!");
    }
?>