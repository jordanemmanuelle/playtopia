<?php
    $connect = mysqli_connect("localhost", "root", "", "playtopia");

    if (mysqli_connect_errno()) {
        echo (mysqli_connect_error());
    }
?>