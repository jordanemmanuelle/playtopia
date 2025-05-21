CEK FOLDER "QUERY" TAKUTNYA ADA YANG BELUM KALIAN CREATE

SEBISA MUNGKIN PENAMAAN FILENYA IKUTIN YANG SUDAH ADA

PASSWORD ADMIN: 123

COLOR CODE
Ijo: #1C2C22
Gold: #CBAE6D

https://chatgpt.com/share/681d6d95-e6e4-8009-87e4-9c6bbd54da35

<?php if (isset($_SESSION['id_user'])): ?>
            <a href="../LoginRegister/Logout.php"><b> Logout </b></a>
        <?php else: ?>
            <a href="../LoginRegister/FormRegister.html"><b>Register</b></a>
            <a href="../LoginRegister/FormLogin.html"><b>Login</b></a>
        <?php endif; ?>