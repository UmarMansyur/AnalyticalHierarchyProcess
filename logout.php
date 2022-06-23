<?php
session_start();
session_destroy();
echo "<script>
            alert('Berhasil');
            window.location.href = './auth/login.php'
        </script>";
