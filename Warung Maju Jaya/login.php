<?php
session_start();
include "koneksi.php";

// LOGIKA LOGOUT DI DALAM FILE LOGIN
// Jika menerima perintah action=logout, hancurkan session saat itu juga
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    // Refresh halaman login agar bersih kembali
    header("Location: login.php");
    exit;
}

// Jika session login sudah ada dan tidak sedang logout, langsung alihkan ke halaman masing-masing
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin.php");
    } else if ($_SESSION['role'] === 'user' || $_SESSION['role'] === 'guest') {
        header("Location: index.php");
    }
    exit;
}

$error = "";

// PROSES LOGIN MEMBER / ADMIN
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        if ($password === $row['password']) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
            $_SESSION['telepon'] = $row['telepon'];
            $_SESSION['alamat'] = $row['alamat'];

            if ($row['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Kata sandi salah!";
        }
    } else {
        $error = "Username tidak ditemukan di database!";
    }
}

// PROSES MASUK SEBAGAI GUEST (TAMU)
if (isset($_POST['login_guest'])) {
    $_SESSION['id'] = 0;
    $_SESSION['username'] = 'guest';
    $_SESSION['role'] = 'guest';
    $_SESSION['nama_lengkap'] = 'Pengunjung Tamu';
    $_SESSION['telepon'] = '';
    $_SESSION['alamat'] = '';

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Warung Bhakti Karya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F0F0F0] min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-md rounded-3xl p-8 shadow-xl space-y-6 border border-gray-100">
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#468191] to-[#2F5B67] flex items-center justify-center text-white mx-auto shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5a11.95 11.95 0 013.293-1.045M21 19.25V9.349m0 0a11.953 11.953 0 00-3.293-1.045M3 19.25V9.349m0 0a11.95 11.95 0 013.293-1.045M3 9.349l8.485-4.242a.75.75 0 01.67 0L21 9.349m-4.243-1.045a11.95 11.95 0 00-9.514 0M18 10.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-10.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
            </div>
            <h1 class="text-xl font-extrabold text-[#468191] tracking-tight uppercase">Warung Bhakti Karya</h1>
            <p class="text-xs text-gray-400">Silakan masuk menggunakan akun Anda atau sebagai Tamu</p>
        </div>

        <?php if ($error !== ""): ?>
            <div class="bg-red-50 text-red-500 text-xs p-3.5 rounded-xl border border-red-100 font-medium">
                ⚠️ <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-4 text-xs">
            <div>
                <label class="block font-bold text-gray-500 mb-1.5 uppercase tracking-wider">Username Pengenal</label>
                <input type="text" name="username" placeholder="Masukkan username" class="w-full bg-gray-50 p-3.5 rounded-xl border focus:outline-none focus:border-[#468191] focus:bg-white transition-all">
            </div>
            <div>
                <label class="block font-bold text-gray-500 mb-1.5 uppercase tracking-wider">Kata Sandi (Password)</label>
                <input type="password" name="password" placeholder="••••••••" class="w-full bg-gray-50 p-3.5 rounded-xl border focus:outline-none focus:border-[#468191] focus:bg-white transition-all">
            </div>
            
            <button type="submit" name="login" class="w-full bg-[#468191] hover:bg-[#2F5B67] text-white font-bold py-3.5 rounded-xl text-xs shadow-md transition-all">
                Masuk ke Aplikasi
            </button>

            <div class="relative flex py-2 items-center">
                <div class="flex-grow border-t border-gray-200"></div>
                <span class="flex-shrink mx-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider">Atau</span>
                <div class="flex-grow border-t border-gray-200"></div>
            </div>

            <button type="submit" name="login_guest" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3.5 rounded-xl text-xs transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-user-secret"></i> Masuk sebagai Tamu (Guest)
            </button>
        </form>
    </div>

</body>
</html>