<?php
session_start();
include "../config/connection.php";
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $query = $conn->query("SELECT * FROM tb_users WHERE email = '$email'");
    $data = mysqli_fetch_assoc($query);
    if ($query) {
        if (password_verify($password, $data['password'])) {
            $_SESSION['id'] = $data['user_id'];
            header('location:../index.php?page=dashboard');
        } else {
            echo "<script>
            alert('Password yang anda masukkan salah');
        </script>";
        }
    } 
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | AHP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">
    <!-- Bootstrap Css -->
    <link href="../assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="../assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
</head>

<body data-sidebar="dark" style="background-color: white;">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-4 col-xl-5 col-md-6 col-sm-12">
                <div class="card overflow-hidden">
                    <div class="bg-primary bg-soft">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-3">
                                    <h5 class="text-primary">Login</h5>
                                    <p>Silahkan login terlebih dahulu!</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="../assets/images/profile-img.png" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="avatar-md profile-user-wid">
                                    <img src="../assets/img/logo.png" alt="" class="img-thumbnail rounded-circle">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Your Email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Your Password" required>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="gridCheck">
                                            <label class="form-check-label" for="gridCheck">
                                                Ingat Saya
                                            </label>
                                        </div>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary w-md" name="login">Masuk</button>
                                    </div>
                                </form>
                            </div>
                            <p class="text-center mt-3">Belum punya akun ? <a href="./registrasi.php">Daftar</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>