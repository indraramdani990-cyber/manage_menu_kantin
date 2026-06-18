<?php
session_start();
include '../config/database.php';

// Jika sudah login, langsung lempar ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit;
}

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi minimal: form tidak boleh kosong
    if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        
        // Cek konfirmasi password sesuai atau tidak
        if ($password !== $confirm_password) {
            $error = "Konfirmasi password tidak cocok!";
        } else {
            // Cek apakah email sudah terdaftar (Email tidak boleh duplikat)
            $cek_email = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
            if (mysqli_num_rows($cek_email) > 0) {
                $error = "Email sudah terdaftar! Gunakan email lain.";
            } else {
                // Enkripsi password dengan Hash bcrypt
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                // Simpan ke database
                $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
                if (mysqli_query($conn, $query)) {
                    $success = "Registrasi berhasil! Silahkan <a href='login.php' class='alert-link'>Login disini</a>.";
                } else {
                    $error = "Terjadi kesalahan sistem, gagal mendaftar.";
                }
            }
        }
    } else {
        $error = "Semua field wajib diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Manajemen Menu Kantin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card-register { max-width: 450px; margin-top: 60px; }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="card card-register shadow-sm w-100 p-4 bg-white rounded">
        <h3 class="text-center mb-4 text-primary fw-bold">Daftar Akun Penjual</h3>
        <p class="text-muted text-center small">Sistem Manajemen Menu Kantin Sekolah</p>
        <hr>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="" method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" name="name" required placeholder="Masukkan nama Anda">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" class="form-control" id="email" name="email" required placeholder="nama@email.com">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="Minimal 6 karakter">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Ulangi password">
            </div>
            <button type="submit" name="register" class="btn btn-primary w-100 py-2 mt-2">Daftar Sekarang</button>
        </form>

        <div class="text-center mt-3">
            <p class="small text-muted">Sudah punya akun? <a href="login.php" class="text-decoration-none">Login ke Sistem</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>