<?php
session_start();

// Jika user sudah login, langsung alihkan ke dashboard [cite: 101]
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
} else {
    // Jika belum login, alihkan ke halaman login di dalam folder auth 
    header("Location: auth/login.php");
    exit;
}
?>