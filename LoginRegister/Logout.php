<?php
    session_start();
    session_unset();
    session_destroy();

    header("Location: FormLogin.html");
    exit();
?>