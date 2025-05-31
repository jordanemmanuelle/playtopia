<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['email'], $_POST['username'], $_POST['password'])) {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashed_password = md5($password); 

        $check_email = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($connect, $check_email);

        if (mysqli_num_rows($result) > 0) {
            echo ("<script>
                alert('Email sudah terdaftar!');
                window.location.href='FormRegister.html';
            </script>");
        } else {
            $sql = "INSERT INTO users (email, username, password)
                    VALUES ('$email', '$username', '$hashed_password')";

            if (mysqli_query($connect, $sql)) {
                echo ("<script>
                    alert('Registrasi Berhasil!');
                    window.location.href='FormLogin.html';
                </script>");
            } else {
                echo ("<script>
                    alert('Registrasi Gagal!');
                    window.location.href='FormRegister.html';
                </script>");
            }
        }
    } else {
        echo ("Isi semua data!");
    }
}
?>