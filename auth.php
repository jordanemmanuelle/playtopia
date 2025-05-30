<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../LoginRegister/FormLogin.html");
    exit();
}

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
