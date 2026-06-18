<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kantin_sekolah";

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Memeriksa koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>