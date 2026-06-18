<?php
session_start();
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id        = $_SESSION['user_id']; // Mengambil ID dari session login [cite: 117]
    $nama_menu      = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $kategori_menu  = $_POST['kategori_menu'];
    $harga          = $_POST['harga'];
    $stok           = $_POST['stok'];
    $status_menu    = $_POST['status_menu'];
    $deskripsi      = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tanggal_input  = $_POST['tanggal_input'];

    $query = "INSERT INTO menu (user_id, nama_menu, kategori_menu, harga, stok, status_menu, deskripsi, tanggal_input) 
              VALUES ('$user_id', '$nama_menu', '$kategori_menu', '$harga', '$stok', '$status_menu', '$deskripsi', '$tanggal_input')";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>