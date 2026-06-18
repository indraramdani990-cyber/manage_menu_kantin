<?php
session_start();
include '../config/database.php';

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Keamanan: Pastikan user_id sesuai [cite: 126, 167]
$query = "DELETE FROM menu WHERE id = '$id' AND user_id = '$user_id'";

if (mysqli_query($conn, $query)) {
    header("Location: index.php");
} else {
    echo "Gagal menghapus.";
}
?>