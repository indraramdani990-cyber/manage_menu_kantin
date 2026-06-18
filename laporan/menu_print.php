<?php
session_start();
include '../config/database.php';

// Proteksi Halaman: Jika belum login, tendang kembali ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$nama_penjual = $_SESSION['name'];
$tanggal_cetak = date('d-m-Y');

// 1. Query untuk menghitung total menu milik penjual yang sedang login
$query_total = "SELECT COUNT(*) as total_menu FROM menu WHERE user_id = '$user_id'";
$result_total = mysqli_query($conn, $query_total);
$data_total = mysqli_fetch_assoc($result_total);
$total_menu = $data_total['total_menu'];

// 2. Query untuk menghitung menu tersedia dan habis
$q_tersedia = mysqli_query($conn, "SELECT COUNT(*) as jml FROM menu WHERE user_id = '$user_id' AND status_menu = 'Tersedia'");
$total_tersedia = mysqli_fetch_assoc($q_tersedia)['jml'];

$q_habis = mysqli_query($conn, "SELECT COUNT(*) as jml FROM menu WHERE user_id = '$user_id' AND status_menu = 'Habis'");
$total_habis = mysqli_fetch_assoc($q_habis)['jml'];

// 3. Query untuk mengambil seluruh data menu milik penjual yang sedang login
$query_menu = "SELECT * FROM menu WHERE user_id = '$user_id' ORDER BY nama_menu ASC";
$result_menu = mysqli_query($conn, $query_menu);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Menu - <?= htmlspecialchars($nama_penjual); ?></title>
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
        
        .btn-hover:hover {
            transform: translateY(-2px);
        }
        
        /* Print Styles - Optimal untuk cetak kertas */
        @media print {
            @page {
                size: A4;
                margin: 2cm;
            }
            
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            
            .no-print {
                display: none !important;
            }
            
            .print-container {
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
            
            .report-box {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
            }
            
            .table-print th, 
            .table-print td {
                border: 1px solid #000 !important;
                padding: 8px !important;
            }
            
            .table-print thead {
                background-color: #f3f4f6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .status-tersedia {
                color: #000 !important;
                font-weight: 600 !important;
            }
            
            .status-habis {
                color: #000 !important;
                font-weight: 600 !important;
            }
        }
    </style>
</head>
<body class="pb-12">

    <!-- Navigation - Hidden saat print -->
    <nav class="bg-white shadow-lg sticky top-0 z-50 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        K
                    </div>
                    <span class="text-xl font-bold text-gray-800">Kantin Digital</span>
                </div>
                
                <div class="flex items-center space-x-2">
                    <a href="../dashboard.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600 font-medium transition-all-300 rounded-lg hover:bg-indigo-50 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="index.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600 font-medium transition-all-300 rounded-lg hover:bg-indigo-50">
                        Menu
                    </a>
                    <button onclick="window.print()" class="ml-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all-300 hover:scale-105 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Laporan
                    </button>
                    <a href="../auth/logout.php" class="ml-2 px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all-300 hover:scale-105">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Action Bar - Hidden saat print -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 no-print">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-1">Laporan Menu</h1>
                <p class="text-indigo-100">Cetak laporan lengkap menu kantin Anda</p>
            </div>
            <button onclick="window.print()" class="btn-hover inline-flex items-center px-6 py-3 bg-white text-indigo-600 font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Sekarang
            </button>
        </div>
    </div>

    <!-- Report Container -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 print-container">
        <div class="report-box bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
            
            <!-- Report Header -->
            <div class="px-8 pt-8 pb-6 border-b-2 border-gray-800">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 uppercase tracking-wide">Kantin Digital</h1>
                        <p class="text-sm text-gray-600 mt-1">Sistem Manajemen Menu Kantin Sekolah</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Tanggal Cetak</p>
                        <p class="text-base font-semibold text-gray-900"><?= $tanggal_cetak; ?></p>
                    </div>
                </div>
                
                <div class="text-center py-4">
                    <h2 class="text-xl font-bold text-gray-900 uppercase">Laporan Daftar Menu Kantin</h2>
                    <div class="mt-2 h-0.5 w-32 bg-gray-800 mx-auto"></div>
                </div>
            </div>

            <!-- Report Info -->
            <div class="px-8 py-6 border-b border-gray-300">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Info Penjual -->
                    <div>
                        <table class="w-full text-sm">
                            <tr>
                                <td class="font-semibold text-gray-900 w-32">Nama Penjual</td>
                                <td class="text-gray-600">:</td>
                                <td class="text-gray-900 font-medium"><?= htmlspecialchars($nama_penjual); ?></td>
                            </tr>
                            <tr>
                                <td class="font-semibold text-gray-900">Tanggal Cetak</td>
                                <td class="text-gray-600">:</td>
                                <td class="text-gray-900 font-medium"><?= $tanggal_cetak; ?></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Statistik Menu -->
                    <div class="flex justify-end">
                        <div class="border border-gray-400 rounded-lg p-4">
                            <p class="text-xs text-gray-600 uppercase tracking-wider mb-1">Total Koleksi Menu</p>
                            <p class="text-3xl font-bold text-gray-900"><?= $total_menu; ?> <span class="text-base font-normal text-gray-600">Item</span></p>
                            <div class="mt-3 pt-3 border-t border-gray-300 flex space-x-6 text-sm">
                                <div>
                                    <span class="text-gray-600">Tersedia:</span>
                                    <span class="font-semibold text-gray-900 ml-1"><?= $total_tersedia; ?></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Habis:</span>
                                    <span class="font-semibold text-gray-900 ml-1"><?= $total_habis; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Table -->
            <div class="px-8 py-6">
                <div class="overflow-x-auto">
                    <table class="table-print w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-center font-bold text-gray-900 uppercase tracking-wider border border-gray-400">No</th>
                                <th class="px-4 py-3 text-left font-bold text-gray-900 uppercase tracking-wider border border-gray-400">Nama Menu</th>
                                <th class="px-4 py-3 text-center font-bold text-gray-900 uppercase tracking-wider border border-gray-400">Kategori</th>
                                <th class="px-4 py-3 text-right font-bold text-gray-900 uppercase tracking-wider border border-gray-400">Harga</th>
                                <th class="px-4 py-3 text-center font-bold text-gray-900 uppercase tracking-wider border border-gray-400">Stok</th>
                                <th class="px-4 py-3 text-center font-bold text-gray-900 uppercase tracking-wider border border-gray-400">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (mysqli_num_rows($result_menu) > 0) {
                                while ($row = mysqli_fetch_assoc($result_menu)) {
                                    $status_class = ($row['status_menu'] == 'Tersedia') ? 'status-tersedia' : 'status-habis';
                                    ?>
                                    <tr class="hover:bg-gray-50 transition-all-300">
                                        <td class="px-4 py-3 text-center font-medium text-gray-700 border border-gray-300"><?= $no++; ?></td>
                                        <td class="px-4 py-3 font-semibold text-gray-900 border border-gray-300"><?= htmlspecialchars($row['nama_menu']); ?></td>
                                        <td class="px-4 py-3 text-center text-gray-700 border border-gray-300"><?= htmlspecialchars($row['kategori_menu']); ?></td>
                                        <td class="px-4 py-3 text-right font-semibold text-gray-900 border border-gray-300">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                        <td class="px-4 py-3 text-center font-medium text-gray-700 border border-gray-300"><?= $row['stok']; ?></td>
                                        <td class="px-4 py-3 text-center border border-gray-300">
                                            <span class="<?= $status_class; ?> px-3 py-1 rounded-full text-xs font-semibold <?= $row['status_menu'] == 'Tersedia' ? 'bg-gray-200' : 'bg-gray-200'; ?>">
                                                <?= $row['status_menu']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php 
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center text-gray-500 border border-gray-300">
                                        Belum ada data menu yang ditambahkan
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Signature Section -->
            <div class="px-8 py-8 bg-gray-50 border-t border-gray-300">
                <div class="grid grid-cols-2 gap-8">
                    <div></div>
                    <div class="text-center">
                        <p class="text-sm text-gray-700 mb-16">Penjual Kantin,</p>
                        <div class="border-b border-gray-800 w-48 mx-auto mb-2"></div>
                        <p class="font-bold text-gray-900"><?= htmlspecialchars($nama_penjual); ?></p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-8 py-3 bg-gray-100 border-t border-gray-300 text-center">
                <p class="text-gray-600 text-xs">
                    © <?= date('Y'); ?> Kantin Digital - Sistem Manajemen Menu Kantin Sekolah
                </p>
            </div>
        </div>
    </div>

</body>
</html>