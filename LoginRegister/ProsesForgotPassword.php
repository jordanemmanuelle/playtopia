<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['email'], $_POST['new_password'], $_POST['confirm_password'])) {
        $email = $_POST['email'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            echo ("<script>
                alert('Password dan konfirmasi password tidak sama!');
                window.location.href='FormForgotPasswor.html';
            </script>");
            exit();
        }

        $hashed_new_password = md5($new_password);

        $checkQuery = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($connect, $checkQuery);

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            $current_password = $user['password'];

            if ($hashed_new_password === $current_password) {
                echo ("<script>
                    alert('Password baru tidak boleh sama dengan password lama!');
                    window.location.href='FormForgotPassword.html';
                </script>");
                exit();
            }

            $updateQuery = "UPDATE users SET password = '$hashed_new_password' WHERE email = '$email'";
            if (mysqli_query($connect, $updateQuery)) {
                echo ("<script>
                    alert('Password berhasil di-reset! Silakan login dengan password baru.');
                    window.location.href='FormLogin.html';
                </script>");
            } else {
                echo ("<script>
                    alert('Terjadi kesalahan saat mengupdate password!');
                    window.location.href='FormForgotPassword.html';
                </script>");
            }
        } else {
            echo ("<script>
                alert('Email tidak ditemukan!');
                window.location.href='FormForgotPasswor.html';
            </script>");
        }
    } else {
        echo ("<script>
            alert('Semua field harus diisi!');
            window.location.href='FormForgotPassword.html';
        </script>");
    }
}
?>
