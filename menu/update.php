<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id             = mysqli_real_escape_string($conn, $_POST['id']);
    $user_id        = $_SESSION['user_id']; // Mengunci aspek keamanan user
    $nama_menu      = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $kategori_menu  = $_POST['kategori_menu'];
    $harga          = $_POST['harga'];
    $stok           = $_POST['stok'];
    $status_menu    = $_POST['status_menu'];
    $deskripsi      = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tanggal_input  = $_POST['tanggal_input'];

    // Keamanan: Update wajib menggunakan WHERE id AND user_id [cite: 125]
    $query = "UPDATE menu SET 
                nama_menu = '$nama_menu', 
                kategori_menu = '$kategori_menu', 
                harga = '$harga', 
                stok = '$stok', 
                status_menu = '$status_menu', 
                deskripsi = '$deskripsi', 
                tanggal_input = '$tanggal_input' 
              WHERE id = '$id' AND user_id = '$user_id'";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($conn);
    }
}
?>