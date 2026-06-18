<?php
session_start();
include '../config/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit;
}

$error = '';

if (isset($_POST['login'])) {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $query  = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name']    = $user['name'];
                $_SESSION['email']   = $user['email'];
                
                header("Location: ../dashboard.php");
                exit;
            } else {
                $error = "Password yang Anda masukkan salah!";
            }
        } else {
            $error = "Email tidak terdaftar dalam sistem!";
        }
    } else {
        $error = "Silahkan masukkan email dan password!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kantin Digital</title>
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
        
        /* Subtle floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .float {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Smooth transitions */
        .transition-all-300 {
            transition: all 0.3s ease;
        }
        
        /* Input focus effect */
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        /* Button hover */
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.3);
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">

    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 gap-0 bg-white rounded-3xl shadow-2xl overflow-hidden">
        
        <!-- Left Side - Branding -->
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 p-12 flex flex-col justify-between text-white relative overflow-hidden">
            
            <!-- Subtle background pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-1/3 translate-y-1/3"></div>
            </div>
            
            <div class="relative z-10">
                <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-lg mb-8">
                    <span class="text-2xl">🏫</span>
                    <span class="font-semibold tracking-wide">KANTIN DIGITAL</span>
                </div>
                
                <div class="space-y-6">
                    <h1 class="text-4xl font-bold leading-tight">
                        Kelola Kantin<br/>Dengan Lebih Mudah
                    </h1>
                    <p class="text-indigo-100 text-lg leading-relaxed">
                        Sistem manajemen menu kantin yang modern dan praktis 
                    </p>
                </div>
            </div>
            
            <div class="relative z-10 mt-12">
                <div class="flex items-center space-x-4 text-indigo-100">
                    <div class="flex -space-x-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-sm">📊</div>
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-sm">🍽️</div>
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-sm">📦</div>
                    </div>
                    <p class="text-sm">KANTIN SMEA</p>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="p-12 bg-white">
            
            <div class="max-w-md mx-auto">
                <!-- Header -->
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 mb-4 float">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang</h2>
                    <p class="text-gray-500">Silakan login ke akun Anda</p>
                </div>

                <!-- Error Message -->
                <?php if (!empty($error)): ?>
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center space-x-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                        </svg>
                        <span class="text-sm font-medium"><?= $error; ?></span>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form action="" method="POST" autocomplete="off" class="space-y-6">
                    
                    <!-- Email Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 1 0-8 0 4 4 0 0 0 8 0Zm0 0v1.5a2.5 2.5 0 0 0 5 0V12a9 9 0 1 0-9 9m4.5-1.206a8.959 8.959 0 0 1-4.5 1.207" />
                                </svg>
                            </div>
                            <input type="email" name="email" required
                                class="input-focus w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all-300" 
                                placeholder="nama@email.com">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>
                            <input type="password" name="password" required
                                class="input-focus w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all-300" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" name="login"
                        class="btn-hover w-full bg-indigo-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg transition-all-300">
                        Masuk
                    </button>

                    <!-- Register Link -->
                    <div class="text-center pt-4">
                        <p class="text-gray-600 text-sm">
                            Belum punya akun? 
                            <a href="register.php" class="text-indigo-600 hover:text-indigo-700 font-medium transition-colors">
                                Daftar Disini
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>