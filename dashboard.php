<?php
session_start();
include 'config/database.php';

// Proteksi Halaman: Jika belum login, tendang ke login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$nama_penjual = $_SESSION['name'];

// 1. Hitung total semua menu milik penjual ini
$q_total = mysqli_query($conn, "SELECT COUNT(*) as jml FROM menu WHERE user_id = '$user_id'");
$total_menu = mysqli_fetch_assoc($q_total)['jml'];

// 2. Hitung total menu yang STATUSNYA 'Tersedia'
$q_tersedia = mysqli_query($conn, "SELECT COUNT(*) as jml FROM menu WHERE user_id = '$user_id' AND status_menu = 'Tersedia'");
$total_tersedia = mysqli_fetch_assoc($q_tersedia)['jml'];

// 3. Hitung total menu yang STATUSNYA 'Habis'
$q_habis = mysqli_query($conn, "SELECT COUNT(*) as jml FROM menu WHERE user_id = '$user_id' AND status_menu = 'Habis'");
$total_habis = mysqli_fetch_assoc($q_habis)['jml'];

// 4. Ambil 5 menu terbaru milik penjual ini
$menu_terbaru = mysqli_query($conn, "SELECT * FROM menu WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kantin Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .transition-all-300 {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.3);
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
        }
    </style>
</head>
<body class="pb-12">

    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        K
                    </div>
                    <span class="text-xl font-bold text-gray-800">Kantin Digital</span>
                </div>
                
                <div class="flex items-center space-x-2">
                    <a href="menu/index.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600 font-medium transition-all-300 rounded-lg hover:bg-indigo-50">
                        Kelola Menu
                    </a>
                    <a href="laporan/menu_print.php" target="_blank" class="px-4 py-2 text-gray-700 hover:text-indigo-600 font-medium transition-all-300 rounded-lg hover:bg-indigo-50">
                        Cetak Laporan
                    </a>
                    <a href="auth/logout.php" class="ml-2 px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all-300 hover:scale-105">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Welcome Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, <span class="text-indigo-600"><?= htmlspecialchars($nama_penjual); ?></span>! 👋</h1>
                    <p class="text-gray-500 mt-1">Silahkan kelola menu makanan dan minuman toko Anda melalui dashboard ini.</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Menu -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 card-hover transition-all-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Total Semua Menu</p>
                        <p class="text-5xl font-bold text-indigo-600"><?= $total_menu; ?></p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Menu Tersedia -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 card-hover transition-all-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Menu Tersedia</p>
                        <p class="text-5xl font-bold text-green-600"><?= $total_tersedia; ?></p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Menu Habis -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 card-hover transition-all-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Menu Habis</p>
                        <p class="text-5xl font-bold text-red-600"><?= $total_habis; ?></p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Menu Table -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-xl font-bold text-gray-800 flex items-center space-x-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                    <span>Menu Yang Tersedia </span>
                </h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Menu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if(mysqli_num_rows($menu_terbaru) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($menu_terbaru)): ?>
                            <tr class="hover:bg-gray-50 transition-all-300">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl flex items-center justify-center text-indigo-600 mr-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <span class="font-semibold text-gray-800"><?= htmlspecialchars($row['nama_menu']); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium">
                                        <?= htmlspecialchars($row['kategori_menu']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-800">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold <?= $row['status_menu'] == 'Tersedia' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                        <span class="w-2 h-2 rounded-full mr-2 <?= $row['status_menu'] == 'Tersedia' ? 'bg-green-500' : 'bg-red-500' ?>"></span>
                                        <?= htmlspecialchars($row['status_menu']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v7m16 0v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-5m16 0h-2.586a1 1 0 0 0-.707.293l-2.414 2.414a1 1 0 0 1-.707.293h-3.172a1 1 0 0 1-.707-.293l-2.414-2.414A1 1 0 0 0 6.586 13H4" />
                                        </svg>
                                        <p class="text-lg font-medium">Belum ada menu yang didaftarkan</p>
                                        <p class="text-sm mt-1">Mulai kelola menu Anda dengan menambahkan menu pertama</p>
                                        <a href="menu/index.php" class="mt-4 px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-all-300 shadow-lg">
                                            Tambah Menu
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>