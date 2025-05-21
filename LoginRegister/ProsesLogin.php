<?php
session_start();

include '../connection.php';

$email = $_POST['email'];
$password = $_POST['password'];

$hashed_password = md5($password);

$sql = "SELECT * FROM users WHERE email ='$email' AND password ='$hashed_password'";
$result = mysqli_query($connect, $sql);

if (mysqli_num_rows($result) === 1) {
    $logged_in_user = mysqli_fetch_assoc($result);
    $_SESSION['id_user'] = $logged_in_user['id_user'];
    $_SESSION['username'] = $logged_in_user['username'];

    header("Location: ../Pages/Home.php");
    exit();
} else {
    echo ("<script>
    alert('Email atau password salah!'); 
    window.location.href='FormLogin.html';
    </script>
    ");
}
?>
