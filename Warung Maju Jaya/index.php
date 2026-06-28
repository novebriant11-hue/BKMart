<?php
session_start();
// Izinkan masuk jika role-nya adalah 'user' ATAU 'guest'
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'user' && $_SESSION['role'] !== 'guest')) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Bhakti Karya - Belanja Sembako Online Premium</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: {
                            primary: '#468191',    /* Biru Teal Utama */
                            secondary: '#2F5B67',  /* Ocean Blue Tua Kontras */
                            light: '#EBF2F4',      /* Soft Blue Tint */
                            bg: '#F0F0F0',          /* Background Bersih Terang (Off-white) */
                        },
                        status: {
                            success: '#10B981',
                            warning: '#F59E0B',
                            danger: '#EF4444',
                        },
                        dark: {
                            bg: '#0D1416',         /* Dark Mode berbasis Ocean Navy */
                            card: '#162023',       
                            text: '#E2E8F0',       
                            border: '#202E32'
                        }
                    },
                    boxShadow: {
                        'soft': '0 10px 35px rgba(70, 129, 145, 0.08)',
                        'button': '0 4px 14px 0 rgba(70, 129, 145, 0.25)',
                        'header': '0 4px 25px rgba(0, 0, 0, 0.02)'
                    }
                }
            }
        }
    </script>
    <style>
        .rotate-music { animation: spinMusic 4s linear infinite; }
        @keyframes spinMusic { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .btn-premium-click { transition: all 0.2s ease; }
        .btn-premium-click:active { transform: scale(0.96); }
        .page-fade { animation: fadeIn 0.25s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-brand-bg dark:bg-dark-bg font-sans antialiased text-gray-800 dark:text-dark-text min-h-screen flex flex-col pb-20 lg:pb-0 transition-colors duration-300">

    <audio id="bgm-player" loop src="sound.mp3" preload="auto"></audio>
    <audio id="sfx-click" src="sound.mp3" preload="auto"></audio>

    <nav class="sticky top-0 z-40 bg-white/95 dark:bg-dark-card/95 border-b border-gray-100 dark:border-dark-border shadow-header transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">
                
                <div class="flex items-center space-x-3 cursor-pointer flex-shrink-0" onclick="playClickSound(); navigateTo('home')">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-brand-primary dream-gradient flex items-center justify-center text-white shadow-md shadow-brand-primary/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5a11.95 11.95 0 013.293-1.045M21 19.25V9.349m0 0a11.953 11.953 0 00-3.293-1.045M3 19.25V9.349m0 0a11.95 11.95 0 013.293-1.045M3 9.349l8.485-4.242a.75.75 0 01.67 0L21 9.349m-4.243-1.045a11.95 11.95 0 00-9.514 0M18 10.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-10.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-sm font-extrabold text-brand-primary dark:text-brand-light tracking-tight uppercase leading-none">Bhakti Karya</h1>
                        <p class="text-[10px] text-gray-400 flex items-center gap-1 mt-1">
                            <i class="fa-solid fa-location-dot text-brand-primary"></i> Alamat: <strong id="header-address-nav" class="max-w-[150px] truncate font-medium text-gray-600 dark:text-gray-300">Memuat rute...</strong>
                        </p>
                    </div>
                </div>

                <div class="hidden md:block flex-1 max-w-md mx-4">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                        <input id="search-input" oninput="searchProduct()" type="text" placeholder="Cari beras, minyak goreng, cemilan instan..." class="w-full bg-gray-50 dark:bg-dark-bg/80 dark:text-white pl-11 pr-4 py-2.5 rounded-xl text-xs border border-transparent focus:outline-none focus:border-brand-primary dark:focus:border-brand-light focus:bg-white dark:focus:bg-dark-card transition-all shadow-inner">
                    </div>
                </div>

                <div class="hidden lg:flex items-center space-x-1 h-full text-xs flex-shrink-0">
                    <button id="nav-btn-home" onclick="playClickSound(); navigateTo('home')" class="nav-link-active px-4 h-20 text-gray-500 dark:text-gray-400 hover:text-brand-primary font-semibold transition-all flex items-center gap-1.5"><i class="fa-solid fa-house"></i> Beranda</button>
                    <button id="nav-btn-riwayat" onclick="playClickSound(); navigateTo('riwayat')" class="px-4 h-20 text-gray-500 dark:text-gray-400 hover:text-brand-primary font-semibold transition-all flex items-center gap-1.5"><i class="fa-solid fa-receipt"></i> Transaksi</button>
                    <button id="nav-btn-keranjang" onclick="playClickSound(); navigateTo('keranjang')" class="px-4 h-20 text-gray-500 dark:text-gray-400 hover:text-brand-primary font-semibold transition-all flex items-center gap-1.5"><i class="fa-solid fa-cart-shopping"></i> Keranjang</button>
                    <button id="nav-btn-profile" onclick="playClickSound(); navigateTo('profile')" class="px-4 h-20 text-gray-500 dark:text-gray-400 hover:text-brand-primary font-semibold transition-all flex items-center gap-1.5"><i class="fa-solid fa-user"></i> Profil</button>
                </div>

                <div class="flex items-center space-x-3 flex-shrink-0">
                    <button onclick="toggleMusicPlayback()" id="music-control-btn" class="btn-premium-click p-2.5 text-gray-400 hover:text-brand-primary rounded-xl bg-gray-50 dark:bg-dark-bg/50 transition-colors" title="Nyalakan/Matikan Musik">
                        <i id="music-status-icon" class="fa-solid fa-music text-sm"></i>
                    </button>

                    <button onclick="playClickSound(); navigateTo('notifikasi')" class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-brand-primary rounded-xl bg-gray-50 dark:bg-dark-bg/50 transition-colors">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <span id="notif-badge" class="absolute top-2 right-2 w-2 h-2 bg-status-danger rounded-full"></span>
                    </button>
                    
                    <button onclick="playClickSound(); navigateTo('keranjang')" class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-brand-primary rounded-xl bg-gray-50 dark:bg-dark-bg/50 transition-colors">
                        <i class="fa-solid fa-basket-shopping text-lg text-brand-primary dark:text-brand-light"></i>
                        <span id="cart-badge" class="absolute -top-1 -right-1 bg-status-danger text-white text-[10px] font-bold px-2 py-0.5 rounded-full min-w-[20px] text-center shadow-md">0</span>
                    </button>

                    <button onclick="playClickSound(); toggleMobileMenu()" class="lg:hidden p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-xl">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                </div>

            </div>
        </div>

        <div id="mobile-menu" class="hidden lg:hidden bg-white dark:bg-dark-card border-t border-gray-100 dark:border-dark-border px-4 py-3 space-y-1">
            <div class="p-2 md:hidden">
                <input id="search-input-mobile" oninput="searchProductMobile()" type="text" placeholder="Cari produk..." class="w-full bg-gray-50 dark:bg-dark-bg p-2.5 rounded-xl text-xs focus:outline-none border border-brand-light">
            </div>
            <button onclick="playClickSound(); navigateTo('home'); toggleMobileMenu()" class="w-full text-left p-3 text-xs font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-zinc-800 block">🏠 Beranda Berbelanja</button>
            <button onclick="playClickSound(); navigateTo('riwayat'); toggleMobileMenu()" class="w-full text-left p-3 text-xs font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-zinc-800 block">📄 Riwayat Pembelian</button>
            <button onclick="playClickSound(); navigateTo('keranjang'); toggleMobileMenu()" class="w-full text-left p-3 text-xs font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-zinc-800 block">🛒 Keranjang Belanja</button>
            <button onclick="playClickSound(); navigateTo('profile'); toggleMobileMenu()" class="w-full text-left p-3 text-xs font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-zinc-800 block">👤 Pengaturan Profil</button>
        </div>
    </nav>

    <nav class="fixed bottom-0 left-0 right-0 bg-white dark:bg-dark-card border-t border-gray-100 dark:border-dark-border lg:hidden flex justify-around items-center h-16 z-50 shadow-lg">
        <button onclick="playClickSound(); navigateTo('home')" class="flex flex-col items-center justify-center text-gray-500 active:text-brand-primary w-12 h-12">
            <i class="fa-solid fa-house text-lg"></i>
            <span class="text-[9px] font-semibold mt-0.5">Beranda</span>
        </button>
        <button onclick="playClickSound(); navigateTo('riwayat')" class="flex flex-col items-center justify-center text-gray-500 active:text-brand-primary w-12 h-12">
            <i class="fa-solid fa-receipt text-lg"></i>
            <span class="text-[9px] font-semibold mt-0.5">Transaksi</span>
        </button>
        <button onclick="playClickSound(); navigateTo('keranjang')" class="flex flex-col items-center justify-center text-gray-500 active:text-brand-primary w-12 h-12 relative">
            <i class="fa-solid fa-cart-shopping text-lg"></i>
            <span class="text-[9px] font-semibold mt-0.5">Keranjang</span>
        </button>
        <button onclick="playClickSound(); navigateTo('profile')" class="flex flex-col items-center justify-center text-gray-500 active:text-brand-primary w-12 h-12">
            <i class="fa-solid fa-user text-lg"></i>
            <span class="text-[9px] font-semibold mt-0.5">Profil</span>
        </button>
    </nav>

    <main class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-1 py-6 flex flex-col">
        
        <div id="sub-header" class="mb-6 mt-2 flex items-center space-x-4 w-full hidden">
            <button onclick="playClickSound(); goBack()" class="w-10 h-10 rounded-xl bg-brand-primary text-white shadow-md flex items-center justify-center hover:bg-brand-secondary active:scale-95 transition-all focus:outline-none" aria-label="Kembali ke halaman sebelumnya">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </button>
            <h2 id="sub-header-title" class="text-xs font-extrabold text-gray-800 dark:text-white tracking-widest uppercase border-l-4 border-brand-primary pl-3">Judul Menu</h2>
        </div>

        <div id="page-home" class="page-fade space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-brand-primary to-[#BAADB3] p-6 rounded-2xl text-white min-h-[140px] flex flex-col justify-between shadow-soft relative overflow-hidden group">
                    <div class="absolute -right-8 -bottom-8 text-9xl opacity-10 group-hover:scale-110 transition-transform">🌾</div>
                    <div>
                        <span class="bg-white/20 text-[10px] px-2.5 py-1 rounded-full font-semibold uppercase tracking-wider">Spesial Pekan Ini</span>
                        <h3 class="font-bold text-xl mt-2 leading-tight">Diskon Bahan Pokok Sembako hingga 30%</h3>
                    </div>
                    <p class="text-xs text-brand-light flex items-center gap-1.5 mt-4"><i class="fa-solid fa-circle-check"></i> Minyak Goreng, Gula & Beras Premium Harga Murah</p>
                </div>
                <div class="bg-gradient-to-br from-amber-600 to-orange-500 p-6 rounded-2xl text-white min-h-[140px] flex flex-col justify-between shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-8 -bottom-8 text-9xl opacity-10 group-hover:scale-110 transition-transform">⚡</div>
                    <div>
                        <span class="bg-white/20 text-[10px] px-2.5 py-1 rounded-full font-semibold uppercase tracking-wider">Klaim Voucher Belanja</span>
                        <h3 class="font-bold text-xl mt-2 leading-tight">Gratis Ongkos Kirim Kilat Langsung</h3>
                    </div>
                    <p class="text-xs text-amber-100 flex items-center gap-1.5 mt-4"><i class="fa-solid fa-bolt animate-bounce"></i> Pengiriman Instan Tanpa Minimum Belanja</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
                <div class="lg:col-span-3 space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 dark:border-dark-border pb-3">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Katalog Komoditas Sembako</h2>
                        <div class="flex space-x-2">
                            <button onclick="playClickSound(); filterCategory(this, 'all')" class="category-btn bg-brand-primary text-white text-xs px-4 py-2 rounded-xl font-semibold shadow-sm transition-all">Semua</button>
                            <button onclick="playClickSound(); filterCategory(this, 'Sembako')" class="category-btn bg-white dark:bg-dark-card text-gray-600 dark:text-gray-400 text-xs px-4 py-2 rounded-xl font-medium shadow-sm hover:bg-gray-50 transition-all">🌾 Sembako</button>
                            <button onclick="playClickSound(); filterCategory(this, 'Minuman')" class="category-btn bg-white dark:bg-dark-card text-gray-600 dark:text-gray-400 text-xs px-4 py-2 rounded-xl font-medium shadow-sm hover:bg-gray-50 transition-all">🧃 Minuman</button>
                            <button onclick="playClickSound(); filterCategory(this, 'Snack')" class="category-btn bg-white dark:bg-dark-card text-gray-600 dark:text-gray-400 text-xs px-4 py-2 rounded-xl font-medium shadow-sm hover:bg-gray-50 transition-all">🍪 Snack</button>
                        </div>
                    </div>
                    <div id="product-container" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
                </div>

                <div class="space-y-4">
                    <div class="bg-white dark:bg-dark-card p-4 rounded-2xl shadow-soft border border-red-100 dark:border-red-950/20">
                        <div class="flex items-center justify-between border-b border-gray-50 dark:border-dark-border pb-3 mb-4">
                            <span class="text-xs font-bold text-gray-800 dark:text-white flex items-center gap-1.5">⚡ Flash Sale</span>
                            <span id="countdown" class="bg-status-danger text-white font-mono text-[11px] px-2.5 py-1 rounded-lg font-bold tracking-wider animate-pulse shadow-sm">02:00:00</span>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">Jangan sampai kehabisan! Diskon kilat sembako segar hanya berlaku sampai penghitung waktu berakhir.</p>
                    </div>

                    <div class="bg-gradient-to-tr from-white to-gray-50 dark:from-dark-card dark:to-zinc-900 p-4 rounded-2xl shadow-soft text-xs space-y-2 text-gray-500 dark:text-gray-400 border border-gray-100 dark:border-dark-border">
                        <p class="font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider text-[10px] mb-2">Jam Operasional Kurir</p>
                        <p><i class="fa-regular fa-clock text-brand-primary mr-1"></i> Setiap Hari: 06.00 - 21.00 WIB</p>
                        <p><i class="fa-solid fa-truck-fast text-brand-primary mr-1"></i> Estimasi Sampai: 15-30 Menit</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="page-detail" class="page-fade hidden fixed inset-0 bg-black/50 z-50 flex items-end md:items-center justify-center">
            <div class="bg-white dark:bg-dark-card w-full md:w-[550px] rounded-t-3xl md:rounded-3xl p-6 space-y-4 border border-gray-100 dark:border-dark-border shadow-2xl max-h-[85vh] overflow-y-auto">
                <div class="flex justify-between items-center border-b pb-2">
                    <span id="detail-category" class="text-[10px] font-bold uppercase tracking-widest text-brand-primary dark:text-brand-light bg-brand-light dark:bg-brand-primary/20 px-3 py-1 rounded-full">Sembako</span>
                    <button onclick="playClickSound(); navigateTo('home')" class="text-gray-400 hover:text-gray-600 p-1"><i class="fa-solid fa-xmark text-lg"></i></button>
                </div>
                <div id="detail-emoji" class="w-full h-44 bg-gray-50 dark:bg-dark-bg/50 rounded-2xl flex items-center justify-center text-5xl shadow-inner relative">
                    <span class="drop-shadow-xl"> Ramen </span>
                </div>
                <h2 id="detail-title" class="text-base font-bold text-gray-800 dark:text-white leading-snug">Nama Komoditas Sembako</h2>

                <div class="p-3 bg-gray-50 dark:bg-dark-bg/60 rounded-xl space-y-1.5 border border-gray-100 dark:border-dark-border text-[11px]">
                    <p class="font-bold text-brand-primary dark:text-brand-light uppercase text-[9px] tracking-wider"><i class="fa-solid fa-tags"></i> Skema Potongan Harga Bertingkat (Kuantitas)</p>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-white dark:bg-dark-card p-1.5 rounded-lg border shadow-xs">
                            <span class="text-gray-400 block text-[9px]">1 - 2 Pcs</span>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Harga Normal</span>
                        </div>
                        <div class="bg-white dark:bg-dark-card p-1.5 rounded-lg border shadow-xs">
                            <span class="text-brand-primary block text-[9px]">3 - 9 Pcs</span>
                            <span class="font-bold text-brand-secondary">Diskon 3%</span>
                        </div>
                        <div class="bg-white dark:bg-dark-card p-1.5 rounded-lg border shadow-xs border-brand-primary">
                            <span class="text-status-success block text-[9px] font-bold">&gt; 10 Pcs (Karton)</span>
                            <span class="font-bold text-status-success">Diskon 7%</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-baseline space-x-3 border-y border-gray-50 dark:border-dark-border py-2">
                    <span id="detail-price" class="text-xl font-bold text-status-danger">Rp0</span>
                    <span id="detail-old-price" class="text-xs text-gray-400 line-through">Rp0</span>
                    <span id="detail-stock" class="text-xs text-status-warning ml-auto font-bold">Stok Terbatas</span>
                </div>
                <div class="text-xs space-y-1">
                    <h3 class="font-bold uppercase text-gray-400 tracking-wider text-[10px]">Deskripsi Ringkas Produk</h3>
                    <p id="detail-desc" class="text-gray-600 dark:text-gray-300 leading-relaxed"></p>
                </div>
                <div class="pt-2">
                    <button onclick="playClickSound(); triggerAddToCart()" class="w-full bg-brand-primary hover:bg-brand-secondary active:scale-[0.98] text-white font-bold py-3.5 rounded-xl shadow-button text-xs transition-all">+ Masukkan Keranjang Belanja</button>
                </div>
            </div>
        </div>

        <div id="page-keranjang" class="page-fade hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <div id="cart-items-container" class="lg:col-span-2 space-y-3"></div>
                <div class="bg-white dark:bg-dark-card p-6 rounded-2xl shadow-soft space-y-4 border border-gray-50 dark:border-dark-border text-xs">
                    <h3 class="font-bold text-sm text-gray-800 dark:text-white border-b border-gray-50 dark:border-dark-border pb-3">Ringkasan Pembayaran Pembelian</h3>
                    <div class="flex justify-between text-gray-500"><span>Subtotal Belanjaan</span><span id="bill-subtotal" class="font-bold text-gray-800 dark:text-white">Rp0</span></div>
                    <div class="flex justify-between text-gray-500"><span>Potongan Tier Grosir</span><span id="bill-tier-discount" class="text-status-success font-bold">-Rp0</span></div>
                    <div class="flex justify-between text-gray-500"><span>Ongkos Kirim Paket Instan</span><span id="bill-shipping-cost" class="font-medium">Rp4.000</span></div>
                    <div class="flex justify-between text-gray-500"><span>Subsidi Kupon Toko</span><span class="text-status-success font-bold">-Rp4.000</span></div>
                    <hr class="border-gray-100 dark:border-dark-border">
                    <div class="flex justify-between text-sm font-bold pt-1"><span>Total Pembayaran</span><span id="bill-total" class="text-status-danger text-lg">Rp0</span></div>
                    <button onclick="playClickSound(); checkCartValidationBeforeCheckout()" class="w-full bg-brand-primary hover:bg-brand-secondary active:scale-[0.98] text-white font-semibold py-3.5 rounded-xl text-xs shadow-button transition-all mt-2">Lanjut Ke Verifikasi Checkout</button>
                </div>
            </div>
        </div>

        <div id="page-checkout" class="page-fade hidden max-w-2xl mx-auto w-full">
            <div class="bg-white dark:bg-dark-card p-6 rounded-2xl shadow-soft space-y-5 border border-gray-50 dark:border-dark-border">
                <div class="space-y-2 text-xs">
                    <p class="font-bold text-brand-primary dark:text-brand-light uppercase text-[10px] tracking-widest flex items-center gap-1.5 border-b pb-2">
                        <i class="fa-solid fa-map-location-dot text-sm"></i> Alamat Tujuan Pengiriman Utama
                    </p>
                    <p id="checkout-customer-name" class="font-bold text-sm text-gray-800 dark:text-white pt-1"></p>
                    <p id="checkout-customer-address" class="text-gray-500 dark:text-gray-400 leading-relaxed"></p>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-dark-bg/60 rounded-xl space-y-4 border border-gray-100 dark:border-dark-border text-xs">
                    <div>
                        <p class="font-bold text-brand-primary dark:text-brand-light uppercase text-[10px] tracking-widest mb-2"><i class="fa-solid fa-truck"></i> Pilih Opsi Kurir Armada Resmi</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <label onclick="playClickSound(); changeArmadaCost(4000)" class="armada-label flex items-center justify-between p-3 rounded-lg bg-white dark:bg-dark-card border border-brand-primary cursor-pointer shadow-xs">
                                <div><p class="font-bold text-gray-700 dark:text-white">Kurir Motor</p><p class="text-[9px] text-gray-400">Muatan Ringan / Ecer</p></div>
                                <input type="radio" checked name="armada" value="motor" class="accent-brand-primary">
                            </label>
                            <label onclick="playClickSound(); changeArmadaCost(15000)" class="armada-label flex items-center justify-between p-3 rounded-lg bg-white dark:bg-dark-card border border-transparent cursor-pointer shadow-xs">
                                <div><p class="font-bold text-gray-700 dark:text-white">Motor Roda Tiga</p><p class="text-[9px] text-gray-400">Hingga 5 Karton Dus</p></div>
                                <input type="radio" name="armada" value="roda3" class="accent-brand-primary">
                            </label>
                            <label onclick="playClickSound(); changeArmadaCost(45000)" class="armada-label flex items-center justify-between p-3 rounded-lg bg-white dark:bg-dark-card border border-transparent cursor-pointer shadow-xs">
                                <div><p class="font-bold text-gray-700 dark:text-white">Mobil Boks / Cargo</p><p class="text-[9px] text-gray-400">Karung Berat / Truk</p></div>
                                <input type="radio" name="armada" value="box" class="accent-brand-primary">
                            </label>
                        </div>
                    </div>
                    <div>
                        <p class="font-bold text-brand-primary dark:text-brand-light uppercase text-[10px] tracking-widest mb-2"><i class="fa-solid fa-clock-rotate-left"></i> Atur Jadwal Pengiriman Kulakan</p>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[9px] text-gray-400 uppercase font-semibold mb-1">Hari/Tanggal</label>
                                <input id="schedule-date" type="date" class="w-full bg-white dark:bg-dark-card p-2 rounded-lg border focus:outline-none dark:text-white text-xs">
                            </div>
                            <div>
                                <label class="block text-[9px] text-gray-400 uppercase font-semibold mb-1">Jendela Waktu</label>
                                <select id="schedule-time" class="w-full bg-white dark:bg-dark-card p-2 rounded-lg border focus:outline-none dark:text-white text-xs">
                                    <option value="08:00 - 11:00">Pagi (08.00 - 11.00 WIB)</option>
                                    <option value="13:00 - 15:00" selected>Siang (13.00 - 15.00 WIB)</option>
                                    <option value="16:00 - 19:00">Sore (16.00 - 19.00 WIB)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3 text-xs">
                    <p class="font-bold text-brand-primary dark:text-brand-light uppercase text-[10px] tracking-widest mb-1">Pilih Metode Sistem Pembayaran</p>
                    
                    <label onclick="playClickSound(); updateRadioVisual(this)" class="payment-label flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-dark-bg/60 cursor-pointer border border-brand-primary transition-all">
                        <span class="font-semibold flex items-center gap-2 text-gray-700 dark:text-gray-200">💵 Tunai / Bayar di Tempat (COD)</span>
                        <input type="radio" checked name="pay" value="cod" class="accent-brand-primary w-4 h-4">
                    </label>
                    
                    <label onclick="playClickSound(); updateRadioVisual(this)" class="payment-label flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-dark-bg/60 cursor-pointer border border-transparent transition-all">
                        <div class="flex flex-col">
                            <span class="font-semibold flex items-center gap-2 text-gray-700 dark:text-gray-200">🤝 WarungPay Later / Tempo 7 Hari</span>
                            <span class="text-[9px] text-brand-secondary font-medium pl-6">Beli barang kulakan hari ini, bayar minggu depan pas laku!</span>
                        </div>
                        <input type="radio" name="pay" value="tempo" class="accent-brand-primary w-4 h-4">
                    </label>

                    <label onclick="playClickSound(); updateRadioVisual(this)" class="payment-label flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-dark-bg/60 cursor-pointer border border-transparent transition-all">
                        <span class="font-semibold flex items-center gap-2 text-gray-700 dark:text-gray-200">📱 QRIS / DANA Dompet Digital</span>
                        <input type="radio" name="pay" value="digital" class="accent-brand-primary w-4 h-4">
                    </label>
                </div>
                <button onclick="playClickSound(); processOrderNow()" class="w-full bg-status-success hover:bg-green-600 active:scale-[0.98] text-white font-bold py-3.5 rounded-xl text-xs shadow-md transition-all pt-2">Selesaikan & Buat Pesanan Sekarang</button>
            </div>
        </div>

        <div id="page-riwayat" class="page-fade hidden max-w-2xl mx-auto w-full space-y-4">
            <div class="bg-white dark:bg-dark-card p-6 rounded-2xl shadow-soft space-y-5 border border-gray-50 dark:border-dark-border text-xs">
                
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-gray-50 dark:border-dark-border pb-3.5">
                    <div>
                        <span class="text-gray-400">ID Nota Belanja: <strong class="text-gray-700 dark:text-gray-200">#WMJ-2026A</strong></span>
                        <p id="live-eta" class="text-[10px] text-brand-primary font-bold mt-0.5"><i class="fa-solid fa-clock animate-pulse"></i> Estimasi Tiba: Menghitung rute tercepat...</p>
                    </div>
                    <span id="tracking-status" class="px-3 py-1 bg-amber-50 dark:bg-amber-950/40 text-status-warning rounded-full font-bold text-[10px] tracking-wider border border-amber-200/30">DALAM PROSES KIRIM</span>
                </div>

                <div class="w-full h-36 bg-gray-50 dark:bg-dark-bg border rounded-xl relative overflow-hidden flex items-center justify-center p-4 shadow-inner">
                    <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#468191_1.5px,transparent_1.5px)] [background-size:16px_16px]"></div>
                    <div class="w-full h-1 bg-dashed border-b-2 border-gray-200 dark:border-zinc-800 relative flex items-center justify-between">
                        <div class="w-7 h-7 bg-brand-primary text-white rounded-full flex items-center justify-center text-[10px] font-bold shadow-md z-10"><i class="fa-solid fa-warehouse"></i></div>
                        <div id="tracking-map-courier" class="w-10 h-10 bg-amber-500 text-white rounded-full flex items-center justify-center text-xs shadow-lg absolute left-0 -top-4 transition-all duration-700 z-20"><i class="fa-solid fa-truck-moving"></i></div>
                        <div class="w-7 h-7 bg-status-success text-white rounded-full flex items-center justify-center text-[10px] font-bold shadow-md z-10"><i class="fa-solid fa-store"></i></div>
                    </div>
                </div>

                <div id="tracking-timeline" class="space-y-5 relative before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-brand-light dark:before:bg-dark-border">
                    <div class="flex items-center space-x-4 relative" id="track-step-2">
                        <span id="track-dot-2" class="w-4 h-4 bg-gray-300 dark:bg-zinc-700 rounded-full z-10 transition-colors"></span>
                        <p id="track-text-2" class="text-gray-400 font-medium">Kurir mendekati lokasi Anda (500 meter lagi)</p>
                    </div>
                    <div class="flex items-center space-x-4 relative" id="track-step-1">
                        <span id="track-dot-1" class="w-4 h-4 bg-gray-300 dark:bg-zinc-700 rounded-full z-10 transition-colors"></span>
                        <p id="track-text-1" class="text-gray-400 font-medium">Barang dipindahkan ke dalam kompartemen armada cargo kurir</p>
                    </div>
                    <div class="flex items-center space-x-4 relative" id="track-step-0">
                        <span id="track-dot-0" class="w-4 h-4 bg-brand-primary rounded-full ring-4 ring-brand-light dark:ring-brand-primary/30 z-10"></span>
                        <p id="track-text-0" class="font-semibold text-brand-primary dark:text-brand-light">Paket sembako selesai diverifikasi adm & dikemas rapi</p>
                    </div>
                </div>

                <div class="pt-4 border-t grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <button onclick="playClickSound(); downloadDigitalInvoice()" class="w-full bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-200 py-2.5 rounded-xl font-bold flex items-center justify-center gap-1.5"><i class="fa-solid fa-receipt"></i> Unduh Nota Digital</button>
                    <button onclick="playClickSound(); triggerQuickReorder()" class="w-full bg-brand-primary text-white py-2.5 rounded-xl font-bold flex items-center justify-center gap-1.5 active:scale-95 transition-transform"><i class="fa-solid fa-rotate-left"></i> Tombol Beli Lagi</button>
                    <button onclick="playClickSound(); openReturnFormModal()" class="w-full bg-red-50 dark:bg-red-950/20 text-status-danger py-2.5 rounded-xl font-bold flex items-center justify-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> Klaim Barang Rusak</button>
                </div>
            </div>
        </div>

        <div id="return-claim-modal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
            <div class="bg-white dark:bg-dark-card max-w-sm w-full rounded-2xl p-5 shadow-2xl border dark:border-dark-border space-y-4 text-xs page-fade">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="font-bold text-sm text-gray-800 dark:text-white flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-status-danger"></i> Ajukan Retur / Klaim Rusak</h3>
                    <button onclick="playClickSound(); closeReturnFormModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-base"></i></button>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block font-bold text-gray-400 mb-1 uppercase text-[9px]">Pilih Barang yang Rusak/Expired *</label>
                        <select class="w-full bg-gray-50 dark:bg-dark-bg p-2.5 rounded-xl border focus:outline-none dark:text-white">
                            <option>Beras Premium Pandan Wangi Murni 10kg</option>
                            <option>Minyak Goreng Sania Refill Pouch 2L</option>
                            <option>Telur Ayam Negeri Fresh - 30 Butir</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-400 mb-1 uppercase text-[9px]">Potret Unggah Bukti Kerusakan Fisik *</label>
                        <div onclick="playClickSound(); alert('Modul Kamera Terintegrasi! Foto berhasil disimpan ke dalam invoice retur.')" class="w-full h-20 border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-xl flex flex-col items-center justify-center gap-1 bg-gray-50 dark:bg-dark-bg cursor-pointer text-gray-400">
                            <i class="fa-solid fa-camera text-base"></i>
                            <span class="text-[9px]">Ambil Foto Melalui Aplikasi</span>
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-400 mb-1 uppercase text-[9px]">Detail Deskripsi Masalah</label>
                        <textarea rows="2" placeholder="Contoh: Kemasan karung robek atau pouch minyak rembes bocor di jalan..." class="w-full bg-gray-50 dark:bg-dark-bg p-2.5 rounded-xl border focus:outline-none dark:text-white"></textarea>
                    </div>
                </div>
                <button onclick="playClickSound(); submitReturnClaimForm()" class="w-full bg-status-danger text-white py-3 rounded-xl font-bold shadow-md">Kirim Laporan Retur Kembalian</button>
            </div>
        </div>

        <div id="page-notifikasi" class="page-fade hidden max-w-xl mx-auto w-full">
            <div class="bg-white dark:bg-dark-card p-4 rounded-xl shadow-soft text-xs flex gap-4 items-start border-l-4 border-brand-primary">
                <span class="text-xl">🎉</span>
                <div>
                    <p class="font-bold text-gray-800 dark:text-white text-sm">Kupon Voucher Gratis Ongkir Berhasil Diklaim!</p>
                    <p class="text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Nikmati layanan pengiriman kurir kilat tanpa potongan biaya kirim khusus transaksi hari ini.</p>
                </div>
            </div>
        </div>

        <div id="page-profile" class="page-fade hidden max-w-2xl mx-auto w-full space-y-4">
            <div class="flex items-center justify-between bg-white dark:bg-dark-card p-5 rounded-2xl shadow-soft border border-gray-50 dark:border-dark-border">
                <div class="flex items-center space-x-4">
                    <div id="profile-avatar" class="w-14 h-14 rounded-2xl bg-brand-primary text-white font-bold flex items-center justify-center text-lg shadow-md">RA</div>
                    <div>
                        <h3 id="profile-display-name" class="text-sm font-bold text-gray-800 dark:text-white">Memuat...</h3>
                        <p id="profile-display-phone" class="text-xs text-gray-400 mt-1">Memuat...</p>
                    </div>
                </div>
                <button id="profile-edit-btn" onclick="playClickSound(); navigateTo('edit-profile')" class="text-xs bg-brand-light dark:bg-brand-primary text-brand-primary dark:text-brand-light px-4 py-2 rounded-xl font-bold hover:bg-brand-primary hover:text-white transition-all">Edit Profil</button>
            </div>
            
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-soft text-xs overflow-hidden border border-gray-50 dark:border-dark-border p-4 space-y-4">
                <h3 class="font-bold text-gray-400 uppercase tracking-wider text-[10px]">⚙️ Pengaturan Pusat Sistem & Musik</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span>🎵 Musik Latar Belakang (BGM Persistent)</span>
                        <input type="checkbox" id="pref-music" onchange="toggleMusicPreference(this)" class="w-4 h-4 accent-brand-primary">
                    </div>
                    <div class="flex items-center justify-between">
                        <span>🔊 Efek Suara Tombol (SFX Klik)</span>
                        <input type="checkbox" id="pref-sfx" checked class="w-4 h-4 accent-brand-primary">
                    </div>
                    <div class="flex items-center justify-between">
                        <span>🌓 Ubah Tema Mode Tampilan (Dark Mode)</span>
                        <button onclick="playClickSound(); toggleDarkMode()" class="w-10 h-5 bg-gray-200 dark:bg-brand-primary rounded-full relative p-0.5 transition-colors"><span id="dark-mode-dot" class="w-4 h-4 bg-white rounded-full absolute left-0.5 top-0.5 transition-all"></span></button>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-soft text-xs divide-y divide-gray-100 dark:divide-dark-border overflow-hidden border border-gray-50 dark:border-dark-border">
                <div onclick="playClickSound(); navigateTo('favorit')" class="p-4 flex items-center justify-between cursor-pointer hover:bg-gray-50 dark:hover:bg-zinc-800/40 transition-colors"><span>❤️ Daftar Kebutuhan Favorit Saya</span><i class="fa-solid fa-chevron-right text-gray-300"></i></div>
                <div onclick="playClickSound(); navigateTo('bantuan')" class="p-4 flex items-center justify-between cursor-pointer hover:bg-gray-50 dark:hover:bg-zinc-800/40 transition-colors"><span>🙋 Pusat Bantuan Admin & FAQ Toko</span><i class="fa-solid fa-chevron-right text-gray-300"></i></div>
            </div>
            <button id="logout-btn" onclick="playClickSound(); window.location.href='login.php?action=logout';" class="w-full bg-red-50 dark:bg-red-950/10 text-status-danger font-semibold py-3.5 rounded-xl text-xs hover:bg-red-100 dark:hover:bg-red-950/20 transition-colors">Keluar dari Akun (Logout)</button>
        </div>

        <div id="page-edit-profile" class="page-fade hidden max-w-xl mx-auto w-full">
            <div class="bg-white dark:bg-dark-card p-5 rounded-2xl shadow-soft space-y-4 text-xs border border-gray-50 dark:border-dark-border">
                <div>
                    <label class="block font-bold text-gray-400 mb-1.5 uppercase text-[9px] tracking-wider">Nama Lengkap Anda *</label>
                    <input id="input-profile-name" type="text" class="w-full bg-gray-50 dark:bg-dark-bg p-3 rounded-xl border border-transparent dark:border-dark-border text-xs focus:outline-none focus:border-brand-primary focus:bg-white dark:focus:bg-dark-card text-gray-800 dark:text-white transition-all">
                </div>
                <div>
                    <label class="block font-bold text-gray-400 mb-1.5 uppercase text-[9px] tracking-wider">Nomor Telepon Kontak Seluler *</label>
                    <input id="input-profile-phone" type="tel" class="w-full bg-gray-50 dark:bg-dark-bg p-3 rounded-xl border border-transparent dark:border-dark-border text-xs focus:outline-none focus:border-brand-primary focus:bg-white dark:focus:bg-dark-card text-gray-800 dark:text-white transition-all">
                </div>
                <div>
                    <label class="block font-bold text-gray-400 mb-1.5 uppercase text-[9px] tracking-wider">Alamat Surel (Email)</label>
                    <input id="input-profile-email" type="email" class="w-full bg-gray-50 dark:bg-dark-bg p-3 rounded-xl border border-transparent dark:border-dark-border text-xs focus:outline-none focus:border-brand-primary focus:bg-white dark:focus:bg-dark-card text-gray-800 dark:text-white transition-all">
                </div>
                <div>
                    <label class="block font-bold text-gray-400 mb-1.5 uppercase text-[9px] tracking-wider">Alamat Tujuan Pengiriman Paket Rumah *</label>
                    <textarea id="input-profile-address" rows="3" class="w-full bg-gray-50 dark:bg-dark-bg p-3 rounded-xl border border-transparent dark:border-dark-border text-xs focus:outline-none focus:border-brand-primary focus:bg-white dark:focus:bg-dark-card text-gray-800 dark:text-white transition-all"></textarea>
                </div>
                <button onclick="playClickSound(); saveProfileChanges()" class="w-full bg-brand-primary text-white font-semibold py-3.5 rounded-xl shadow-md transition-colors mt-2">Simpan Perubahan Data Profil</button>
            </div>
        </div>

        <div id="page-bantuan" class="page-fade hidden max-w-2xl mx-auto w-full space-y-4">
            <div class="space-y-3">
                <div class="bg-white dark:bg-dark-card rounded-xl p-4 shadow-soft border border-gray-50 dark:border-dark-border">
                    <button onclick="playClickSound(); toggleFaq(this)" class="w-full flex justify-between items-center font-bold text-left text-gray-800 dark:text-white">
                        <span>Berapa lama estimasi waktu pengiriman barang?</span><i class="fa-solid fa-chevron-down text-gray-400"></i>
                    </button>
                    <p class="text-gray-500 dark:text-gray-400 mt-3 hidden leading-relaxed text-[11px]">Pengiriman instan kami memakan waktu sekitar 15-30 menit setelah pembayaran dikonfirmasi, diantarkan langsung oleh kurir internal toko kami.</p>
                </div>
                <div class="bg-white dark:bg-dark-card rounded-xl p-4 shadow-soft border border-gray-50 dark:border-dark-border">
                    <button onclick="playClickSound(); toggleFaq(this)" class="w-full flex justify-between items-center font-bold text-left text-gray-800 dark:text-white">
                        <span>Apakah transaksi metode COD aman dilakukan?</span><i class="fa-solid fa-chevron-down text-gray-400"></i>
                    </button>
                    <p class="text-gray-500 dark:text-gray-400 mt-3 hidden leading-relaxed text-[11px]">Sangat aman! Anda cukup menyerahkan uang tunai pas kepada kurir resmi toko kami saat produk sembako telah Anda terima di tangan.</p>
                </div>
            </div>
            <div class="bg-white dark:bg-dark-card p-5 rounded-2xl shadow-soft text-center text-xs space-y-3 max-w-sm mx-auto w-full">
                <p class="text-gray-400">Mengalami kendala kritis lain?</p>
                <a href="https://wa.me/628515550912" target="_blank" class="inline-flex items-center justify-center gap-2 w-full bg-status-success text-white py-2.5 rounded-xl font-semibold shadow-sm transition-transform active:scale-[0.97]">
                    <i class="fa-brands fa-whatsapp text-base"></i> Hubungi CS via WhatsApp Live Chat
                </a>
            </div>
        </div>

        <div id="page-favorit" class="page-fade hidden">
            <div id="favorit-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4"></div>
        </div>

    </main>

    <footer class="hidden lg:block bg-white dark:bg-dark-card border-t border-gray-100 dark:border-dark-border mt-auto py-6 text-center text-xs text-gray-400">
        <p>&copy; 2026 Warung Bhakti Karya - Platform Belanja Grosir Sembako Online Terpercaya.</p>
    </footer>

    <script>
        const ALL_PRODUCTS = [
            <?php
            include "koneksi.php";
            $query = mysqli_query($koneksi, "SELECT p.*, k.nama_kategori FROM produk p JOIN kategori k ON p.kategori_id = k.id");
            while ($row = mysqli_fetch_assoc($query)) {
                echo "{
                    id: " . $row['id'] . ",
                    name: \"" . addslashes($row['nama_produk']) . "\",
                    category: \"" . $row['nama_kategori'] . "\",
                    price: " . $row['harga'] . ",
                    oldPrice: " . ($row['harga_lama'] ? $row['harga_lama'] : 'null') . ",
                    image: \"" . $row['gambar'] . "\",
                    stock: " . $row['stok'] . ",
                    statusBadge: \"" . $row['badge_status'] . "\",
                    desc: \"" . addslashes($row['deskripsi']) . "\",
                    fav: " . ($row['is_favorit'] ? 'true' : 'false') . "
                },";
            }
            ?>
        ];

        // SINKRONISASI DATA PROFIL BERBASIS MYSQL DAN MENDUKUNG VALIDASI ROLE GUEST
        let customerProfile = {
            name: "<?php echo $_SESSION['nama_lengkap']; ?>",
            phone: "<?php echo $_SESSION['telepon'] ? $_SESSION['telepon'] : 'Tidak Ada Nomor'; ?>",
            email: "<?php echo $_SESSION['username']; ?>@email.com",
            address: "<?php echo addslashes($_SESSION['alamat']); ?>",
            isGuest: <?php echo ($_SESSION['role'] === 'guest') ? 'true' : 'false'; ?>
        };

        let cart = [{ productId: 1, qty: 2 }, { productId: 2, qty: 1 }];
        let currentSelectedProductId = 1;
        let navigationHistory = ['home'];
        let currentShippingCost = 4000; 
        let liveTrackingTimer = null;

        const bgm = document.getElementById('bgm-player');
        const clickSfx = document.getElementById('sfx-click');
        let isMusicPlaying = false;

        bgm.volume = 0.20;    
        clickSfx.volume = 0.40; 

        function toggleMusicPreference(el) {
            toggleMusicPlayback();
            localStorage.setItem('musicStatus', el.checked);
        }

        function toggleMusicPlayback() {
            const musicBtn = document.getElementById('music-control-btn');
            const musicIcon = document.getElementById('music-status-icon');
            
            if (isMusicPlaying) {
                bgm.pause();
                musicIcon.className = "fa-solid fa-music text-sm";
                if(musicBtn) musicBtn.classList.remove('bg-brand-primary/10', 'text-brand-primary', 'rotate-music');
            } else {
                bgm.play().catch(err => console.log("Muted until interaction."));
                musicIcon.className = "fa-solid fa-compact-disc text-sm";
                if(musicBtn) musicBtn.classList.add('bg-brand-primary/10', 'text-brand-primary', 'rotate-music');
            }
            isMusicPlaying = !isMusicPlaying;
        }

        function playClickSound() {
            const sfxCheckbox = document.getElementById('pref-sfx');
            if(sfxCheckbox && !sfxCheckbox.checked) return;
            clickSfx.currentTime = 0;
            clickSfx.play().catch(() => {});
        }

        window.addEventListener('DOMContentLoaded', () => {
            renderProductGrid(ALL_PRODUCTS);
            updateCartBadges();
            syncProfileDataToInputs();

            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
                document.getElementById('dark-mode-dot').classList.add('left-5');
                document.getElementById('dark-mode-dot').classList.remove('left-0.5');
            }
            if (localStorage.getItem('musicStatus') === 'true') {
                document.getElementById('pref-music').checked = true;
                toggleMusicPlayback();
            }
            
            const dateInput = document.getElementById('schedule-date');
            if (dateInput) {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                dateInput.value = tomorrow.toISOString().split('T')[0];
            }
        });

        function navigateTo(pageId, isBackAction = false) {
            const pages = ['home', 'detail', 'keranjang', 'checkout', 'riwayat', 'profile', 'notifikasi', 'favorit', 'edit-profile', 'bantuan'];
            pages.forEach(p => {
                const el = document.getElementById(`page-${p}`);
                if (el) el.classList.add('hidden');
            });
            
            const targetPage = document.getElementById(`page-${pageId}`);
            if (targetPage) targetPage.classList.remove('hidden');

            const subHeader = document.getElementById('sub-header');
            const subHeaderTitle = document.getElementById('sub-header-title');

            if(pageId === 'home') {
                if (subHeader) subHeader.classList.add('hidden');
            } else {
                if (subHeader) {
                    subHeader.classList.remove('hidden');
                    subHeaderTitle.innerText = pageId.replace('-', ' ').toUpperCase();
                }
            }

            if (pageId === 'keranjang') renderCartPage();
            if (pageId === 'favorit') renderFavoritPage();
            if (pageId === 'riwayat') runLiveTrackingDashboard();

            if (!isBackAction && navigationHistory[navigationHistory.length - 1] !== pageId) {
                navigationHistory.push(pageId);
            }

            const navIds = ['home', 'riwayat', 'keranjang', 'profile'];
            navIds.forEach(id => {
                const btn = document.getElementById(`nav-btn-${id}`);
                if (btn) {
                    if (id === pageId || (id === 'profile' && (pageId === 'edit-profile' || pageId === 'favorit' || pageId === 'bantuan'))) {
                        btn.className = "nav-link-active px-4 h-20 text-brand-primary dark:text-brand-light font-bold border-b-2 border-brand-primary transition-all flex items-center gap-1.5";
                    } else {
                        btn.className = "px-4 h-20 text-gray-500 dark:text-gray-400 hover:text-brand-primary font-medium transition-all flex items-center gap-1.5";
                    }
                }
            });
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function goBack() {
            if (navigationHistory.length > 1) {
                navigationHistory.pop();
                const prevPage = navigationHistory[navigationHistory.length - 1];
                navigateTo(prevPage, true);
            } else {
                navigateTo('home');
            }
        }

        function toggleMobileMenu() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        }

        function updateRadioVisual(clickedLabel) {
            document.querySelectorAll('.payment-label').forEach(label => {
                label.classList.remove('border-brand-primary');
                label.classList.add('border-transparent');
                label.querySelector('input').checked = false;
            });
            clickedLabel.classList.remove('border-transparent');
            clickedLabel.classList.add('border-brand-primary');
            clickedLabel.querySelector('input').checked = true;
        }

        function renderProductGrid(products) {
            const container = document.getElementById('product-container');
            if (!container) return;
            container.innerHTML = '';
            if(products.length === 0) {
                container.innerHTML = '<p class="text-xs text-center text-gray-400 col-span-full py-12">Produk kebutuhan tidak ditemukan...</p>';
                return;
            }
            products.forEach(p => {
                container.innerHTML += `
                    <div class="product-card-web bg-white dark:bg-dark-card rounded-2xl p-4 shadow-soft relative flex flex-col justify-between border border-transparent hover:border-brand-light transition-all duration-300 group">
                        <span class="absolute top-3 left-3 bg-brand-primary text-white text-[8px] font-bold px-2 py-0.5 rounded-lg z-10">${p.statusBadge}</span>
                        <button onclick="playClickSound(); toggleFavDirect(event, ${p.id})" class="absolute top-3 right-3 z-10 text-sm ${p.fav ? 'text-red-500' : 'text-gray-300 dark:text-zinc-600'}"><i class="fa-solid fa-heart"></i></button>
                        <div onclick="playClickSound(); openProductDetail(${p.id})" class="w-full h-28 bg-gray-50 dark:bg-dark-bg/50 rounded-xl flex items-center justify-center overflow-hidden mb-3 cursor-pointer">
                            <img src="${p.image}" alt="${p.name}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.onerror=null; this.src='https://images.placeholders.dev/?width=150&height=150&text=Sembako+Bulk&text_size=14';">
                        </div>
                        <div onclick="playClickSound(); openProductDetail(${p.id})" class="cursor-pointer space-y-1">
                            <h4 class="text-xs font-semibold text-gray-800 dark:text-dark-text line-clamp-2 h-8">${p.name}</h4>
                            <div class="flex items-center justify-between pt-1">
                                <span class="text-xs font-bold text-status-danger">Rp${p.price.toLocaleString('id-ID')}</span>
                                <span class="text-[10px] text-gray-400 font-bold">Stok: ${p.stock}</span>
                            </div>
                        </div>
                    </div>`;
            });
        }

        function openProductDetail(id) {
            currentSelectedProductId = id;
            const p = ALL_PRODUCTS.find(prod => prod.id === id);
            if(p) {
                document.getElementById('detail-emoji').innerHTML = `<img src="${p.image}" alt="${p.name}" class="h-full w-full object-cover rounded-t-3xl md:rounded-3xl" onerror="this.onerror=null; this.src='https://images.placeholders.dev/?width=300&height=300&text=Detail+Produk';">`;
                document.getElementById('detail-title').innerText = p.name;
                document.getElementById('detail-category').innerText = p.category;
                document.getElementById('detail-price').innerText = `Rp${p.price.toLocaleString('id-ID')}`;
                document.getElementById('detail-old-price').innerText = p.oldPrice ? `Rp${p.oldPrice.toLocaleString('id-ID')}` : '';
                document.getElementById('detail-desc').innerText = p.desc;
                document.getElementById('detail-stock').innerText = `Tersisa ${p.stock} Paket`;
                
                document.getElementById('page-detail').classList.remove('hidden');
            }
        }

        function triggerAddToCart() {
            const existing = cart.find(item => item.productId === currentSelectedProductId);
            if(existing) existing.qty++;
            else cart.push({ productId: currentSelectedProductId, qty: 1 });
            updateCartBadges();
            alert('Sukses menambahkan paket kebutuhan ke keranjang!');
            document.getElementById('page-detail').classList.add('hidden');
            navigateTo('keranjang');
        }

        function updateCartBadges() {
            document.getElementById('cart-badge').innerText = cart.reduce((acc, item) => acc + item.qty, 0);
        }

        function renderCartPage() {
            const container = document.getElementById('cart-items-container');
            if (!container) return;
            container.innerHTML = '';
            let subtotal = 0;
            let totalGrosirDiscount = 0;

            if(cart.length === 0) {
                container.innerHTML = `
                    <div class="text-center bg-white dark:bg-dark-card rounded-2xl py-12 space-y-2 border">
                        <span class="text-5xl block">🛒</span>
                        <p class="text-xs text-gray-400">Keranjang Belanjaan Anda Kosong.</p>
                    </div>`;
                document.getElementById('bill-subtotal').innerText = 'Rp0';
                document.getElementById('bill-tier-discount').innerText = '-Rp0';
                document.getElementById('bill-total').innerText = 'Rp0';
                return;
            }

            cart.forEach((item, index) => {
                const p = ALL_PRODUCTS.find(prod => prod.id === item.productId);
                if(p) {
                    let itemBaseTotal = p.price * item.qty;
                    let currentTierDiscount = 0;
                    
                    if (item.qty >= 3 && item.qty <= 9) {
                        currentTierDiscount = Math.round(itemBaseTotal * 0.03); 
                    } else if (item.qty >= 10) {
                        currentTierDiscount = Math.round(itemBaseTotal * 0.07); 
                    }

                    subtotal += itemBaseTotal;
                    totalGrosirDiscount += currentTierDiscount;

                    container.innerHTML += `
                        <div class="bg-white dark:bg-dark-card p-4 rounded-xl shadow-soft flex items-center justify-between border border-gray-50/60 dark:border-dark-border">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gray-50 dark:bg-dark-bg/60 rounded-xl flex-shrink-0 flex items-center justify-center overflow-hidden">
                                    <img src="${p.image}" alt="${p.name}" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='https://images.placeholders.dev/?width=50&height=50&text=Cart';">
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-800 dark:text-white line-clamp-1">${p.name}</h4>
                                    <p class="text-xs font-bold text-brand-primary dark:text-brand-light mt-0.5">
                                        Rp${p.price.toLocaleString('id-ID')} 
                                        ${currentTierDiscount > 0 ? `<span class="text-[9px] text-status-success font-bold ml-1">(Tier Grosir Hemat -Rp${currentTierDiscount.toLocaleString('id-ID')})</span>` : ''}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2.5">
                                <button onclick="playClickSound(); adjustQty(${index}, -1)" class="w-7 h-7 bg-gray-100 dark:bg-dark-bg text-gray-800 dark:text-white rounded-lg font-bold text-xs flex items-center justify-center shadow-sm hover:bg-gray-200 transition-colors">-</button>
                                <span class="text-xs font-bold w-4 text-center">${item.qty}</span>
                                <button onclick="playClickSound(); adjustQty(${index}, 1)" class="w-7 h-7 bg-brand-light dark:bg-brand-primary text-brand-primary dark:text-brand-light rounded-lg font-bold text-xs flex items-center justify-center shadow-sm hover:bg-brand-primary hover:text-white transition-colors">+</button>
                            </div>
                        </div>`;
                }
            });

            const shippingCalculated = currentShippingCost;
            const grandTotalFinal = subtotal - totalGrosirDiscount + shippingCalculated - 4000; 

            document.getElementById('bill-subtotal').innerText = `Rp${subtotal.toLocaleString('id-ID')}`;
            document.getElementById('bill-tier-discount').innerText = `-Rp${totalGrosirDiscount.toLocaleString('id-ID')}`;
            document.getElementById('bill-shipping-cost').innerText = `Rp${shippingCalculated.toLocaleString('id-ID')}`;
            document.getElementById('bill-total').innerText = `Rp${Math.max(0, grandTotalFinal).toLocaleString('id-ID')}`;
        }

        function adjustQty(index, amt) {
            cart[index].qty += amt;
            if(cart[index].qty <= 0) cart.splice(index, 1);
            updateCartBadges();
            renderCartPage();
        }

        function renderFavoritPage() {
            const container = document.getElementById('favorit-container');
            if (!container) return;
            container.innerHTML = '';
            const favs = ALL_PRODUCTS.filter(p => p.fav);
            
            if(favs.length === 0) {
                container.innerHTML = '<p class="text-xs text-center text-gray-400 col-span-full py-12">Belum ada produk kebutuhan favorit.</p>';
                return;
            }
            favs.forEach(p => {
                container.innerHTML += `
                    <div class="bg-white dark:bg-dark-card p-4 rounded-2xl shadow-soft text-center relative border border-gray-50 dark:border-dark-border flex flex-col justify-between">
                        <button onclick="playClickSound(); removeFav(${p.id})" class="absolute top-2 right-2 text-red-500 text-sm"><i class="fa-solid fa-heart"></i></button>
                        <div class="w-full h-20 bg-gray-50 rounded-xl overflow-hidden flex items-center justify-center my-2">
                            <img src="${p.image}" alt="${p.name}" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='https://images.placeholders.dev/?width=100&height=100&text=Fav';">
                        </div>
                        <h4 class="text-xs font-semibold truncate text-gray-800 dark:text-white">${p.name}</h4>
                        <button onclick="playClickSound(); openProductDetail(${p.id})" class="mt-3 w-full bg-brand-primary text-white text-[11px] py-2 rounded-xl font-medium transition-colors">Beli Sekarang</button>
                    </div>`;
            });
        }

        function checkCartValidationBeforeCheckout() {
            if (customerProfile.isGuest || !customerProfile.name.trim() || !customerProfile.phone.trim() || !customerProfile.address.trim()) {
                alert("⚠️ Data Pengiriman Belum Lengkap: Silakan lengkapi profil akun belanja Anda terlebih dahulu!");
                navigateTo('edit-profile');
            } else {
                navigateTo('checkout');
            }
        }

        function syncProfileDataToInputs() {
            document.getElementById('input-profile-name').value = customerProfile.name;
            document.getElementById('input-profile-phone').value = customerProfile.phone;
            document.getElementById('input-profile-email').value = customerProfile.email;
            document.getElementById('input-profile-address').value = customerProfile.address;
            const avatar = document.getElementById('profile-avatar');
            const logoutBtn = document.getElementById('logout-btn');

            if (customerProfile.isGuest) {
                document.getElementById('profile-display-name').innerText = "Akun Tamu (Guest Mode)";
                document.getElementById('profile-display-phone').innerText = "Lengkapi profil untuk melakukan checkout";
                document.getElementById('header-address-nav').innerText = "Lokasi belum ditentukan";
                if(avatar) avatar.innerText = "G";
                if(logoutBtn) logoutBtn.innerText = "Kembali ke Halaman Login";
            } else {
                document.getElementById('profile-display-name').innerText = customerProfile.name;
                document.getElementById('profile-display-phone').innerText = customerProfile.phone;
                document.getElementById('header-address-nav').innerText = customerProfile.address;
                document.getElementById('checkout-customer-name').innerText = `${customerProfile.name} (${customerProfile.phone})`;
                document.getElementById('checkout-customer-address').innerText = customerProfile.address;
                if(avatar) avatar.innerText = customerProfile.name.substring(0,2).toUpperCase();
                if(logoutBtn) logoutBtn.innerText = "Keluar dari Akun (Logout)";
            }
        }

        function saveProfileChanges() {
            const n = document.getElementById('input-profile-name').value;
            const p = document.getElementById('input-profile-phone').value;
            const a = document.getElementById('input-profile-address').value;
            if(!n.trim() || !p.trim() || !a.trim()) { alert("❌ Gagal: Data berlabel * wajib diisi."); return; }
            customerProfile.name = n; customerProfile.phone = p; customerProfile.address = a;
            customerProfile.email = document.getElementById('input-profile-email').value; customerProfile.isGuest = false;
            syncProfileDataToInputs(); alert("✓ Sukses mengupdate data profil!"); goBack();
        }

        function removeFav(id) { const p = ALL_PRODUCTS.find(prod => prod.id === id); if(p) p.fav = false; renderFavoritPage(); }
        function toggleFavDirect(e, id) { e.stopPropagation(); const p = ALL_PRODUCTS.find(prod => prod.id === id); if(p) p.fav = !p.fav; searchProduct(); }
        
        function processOrderNow() { 
            const selectedMethod = document.querySelector('input[name="pay"]:checked').value;
            const targetDate = document.getElementById('schedule-date').value || "Besok";
            const targetTime = document.getElementById('schedule-time').value;

            alert(`✓ Sukses: Pesanan diproses! Pengiriman dijalankan menuju lokasi tujuan.`);
            
            cart = []; 
            updateCartBadges(); 
            navigateTo('riwayat'); 
        }
        
        function searchProduct() { const q = document.getElementById('search-input').value.toLowerCase(); renderProductGrid(ALL_PRODUCTS.filter(p => p.name.toLowerCase().includes(q))); }
        function searchProductMobile() { const q = document.getElementById('search-input-mobile').value.toLowerCase(); renderProductGrid(ALL_PRODUCTS.filter(p => p.name.toLowerCase().includes(q))); }

        function filterCategory(btnElement, cat) {
            document.querySelectorAll('.category-btn').forEach(b => {
                b.className = "category-btn bg-white dark:bg-dark-card text-gray-600 dark:text-gray-400 text-xs px-4 py-2 rounded-xl font-medium shadow-sm hover:bg-gray-50 transition-all";
            });
            if (btnElement) {
                btnElement.className = "category-btn bg-brand-primary text-white text-xs px-4 py-2 rounded-xl font-semibold shadow-sm transition-all";
            }
            renderProductGrid(cat === 'all' ? ALL_PRODUCTS : ALL_PRODUCTS.filter(p => p.category === cat));
        }

        function toggleFaq(btn) { btn.nextElementSibling.classList.toggle('hidden'); btn.querySelector('i').classList.toggle('fa-chevron-down'); btn.querySelector('i').classList.toggle('fa-chevron-up'); }
        
        function toggleDarkMode() { 
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('darkMode', isDark);

            const dot = document.getElementById('dark-mode-dot');
            if(isDark) {
                dot.classList.add('left-5');
                dot.classList.remove('left-0.5');
            } else {
                dot.classList.remove('left-5');
                dot.classList.add('left-0.5');
            }
        }

        function changeArmadaCost(costAmt) {
            currentShippingCost = costAmt;
            document.querySelectorAll('.armada-label').forEach(label => {
                label.className = "armada-label flex items-center justify-between p-3 rounded-lg bg-white dark:bg-dark-card border border-transparent cursor-pointer shadow-xs";
                label.querySelector('input').checked = false;
            });
            const selectedLabel = window.event.currentTarget;
            selectedLabel.className = "armada-label flex items-center justify-between p-3 rounded-lg bg-white dark:bg-dark-card border border-brand-primary cursor-pointer shadow-xs";
            selectedLabel.querySelector('input').checked = true;
        }

        function runLiveTrackingDashboard() {
            if (liveTrackingTimer) clearInterval(liveTrackingTimer);

            const mapCourier = document.getElementById('tracking-map-courier');
            const etaIndicator = document.getElementById('live-eta');
            const mainStatusBadge = document.getElementById('tracking-status');
            
            const txt0 = document.getElementById('track-text-0');
            const txt1 = document.getElementById('track-text-1');
            const txt2 = document.getElementById('track-text-2');
            const dot0 = document.getElementById('track-dot-0');
            const dot1 = document.getElementById('track-dot-1');
            const dot2 = document.getElementById('track-dot-2');

            let step = 0;
            mapCourier.style.left = "0%";
            mapCourier.innerHTML = `<i class="fa-solid fa-box-open"></i>`;
            mainStatusBadge.innerText = "MEMPROSES PESANAN";
            mainStatusBadge.className = "px-3 py-1 bg-blue-50 text-blue-600 rounded-full font-bold text-[10px] tracking-wider";
            etaIndicator.innerHTML = `<i class="fa-solid fa-circle-notch animate-spin"></i> Menyiapkan muatan armada kurir...`;

            liveTrackingTimer = setInterval(() => {
                step += 1;
                if (step === 3) {
                    mapCourier.style.left = "45%";
                    mapCourier.innerHTML = `<i class="fa-solid fa-truck-fast animate-pulse"></i>`;
                    mainStatusBadge.innerText = "KURIR DALAM PERJALANAN";
                    mainStatusBadge.className = "px-3 py-1 bg-amber-50 text-status-warning rounded-full font-bold text-[10px] tracking-wider";
                    etaIndicator.innerHTML = `<i class="fa-solid fa-clock-rotate-left text-amber-500"></i> Estimasi Waktu Tiba (ETA): <strong>12 Menit Lagi</strong>`;
                    
                    txt0.className = "text-gray-400 font-medium";
                    dot0.className = "w-4 h-4 bg-gray-300 dark:bg-zinc-700 rounded-full z-10";
                    txt1.className = "font-bold text-brand-primary dark:text-brand-light";
                    dot1.className = "w-4 h-4 bg-brand-primary rounded-full ring-4 ring-brand-light dark:ring-brand-primary/30 z-10";
                }
                if (step === 6) {
                    mapCourier.style.left = "85%";
                    mapCourier.innerHTML = `<i class="fa-solid fa-person-biking text-sm"></i>`;
                    mainStatusBadge.innerText = "KURIR DEKAT (500 METER)";
                    mainStatusBadge.className = "px-3 py-1 bg-orange-50 text-orange-600 rounded-full font-bold text-[10px] tracking-wider";
                    etaIndicator.innerHTML = `<i class="fa-solid fa-bell text-orange-500 animate-bounce"></i> Kurir sudah dekat! Bersiap di lokasi tujuan.`;
                    
                    txt1.className = "text-gray-400 font-medium";
                    dot1.className = "w-4 h-4 bg-gray-300 dark:bg-zinc-700 rounded-full z-10";
                    txt2.className = "font-bold text-brand-primary dark:text-brand-light";
                    dot2.className = "w-4 h-4 bg-brand-primary rounded-full ring-4 ring-brand-light dark:ring-brand-primary/30 z-10";
                }
                if (step >= 9) {
                    clearInterval(liveTrackingTimer);
                    mapCourier.style.left = "100%";
                    mapCourier.innerHTML = `<i class="fa-solid fa-house-circle-check"></i>`;
                    mainStatusBadge.innerText = "PESANAN SELESAI TIBA";
                    mainStatusBadge.className = "px-3 py-1 bg-green-50 text-status-success rounded-full font-bold text-[10px] tracking-wider border border-green-200/50";
                    etaIndicator.innerHTML = `<i class="fa-solid fa-circle-check text-status-success"></i> Sembako mendarat sukses.`;
                    
                    txt2.className = "font-bold text-status-success";
                    dot2.className = "w-4 h-4 bg-status-success rounded-full ring-4 ring-green-100 dark:ring-green-950/30 z-10";
                }
            }, 1000);
        }

        function triggerQuickReorder() {
            cart = [
                { productId: 1, qty: 3 },
                { productId: 2, qty: 5 }
            ];
            updateCartBadges();
            alert("✓ Sukses: Muatan belanjaan sebelumnya dimasukkan kembali ke keranjang!");
            navigateTo('keranjang');
        }

        function downloadDigitalInvoice() {
            alert("✓ Berhasil mengunduh berkas E-Invoice resmi #WMJ-2026A.");
        }

        function openReturnFormModal() { document.getElementById('return-claim-modal').classList.remove('hidden'); }
        function closeReturnFormModal() { document.getElementById('return-claim-modal').classList.add('hidden'); }
        function submitReturnClaimForm() {
            alert("✓ Laporan Terkirim: Foto bukti fisik kerusakan sukses masuk sistem.");
            closeReturnFormModal();
        }

        let timeRemaining = 7995; 
        setInterval(() => {
            let hours = Math.floor(timeRemaining / 3600); let minutes = Math.floor((timeRemaining % 3600) / 60); let seconds = timeRemaining % 60;
            document.getElementById('countdown').innerText = `${hours < 10 ? '0'+hours : hours}:${minutes < 10 ? '0'+minutes : minutes}:${seconds < 10 ? '0'+seconds : seconds}`;
            if(timeRemaining > 0) timeRemaining--;
        }, 1000);
    </script>
</body>
</html>