<?php
session_start();

// PROTEKSI PANEL ADMIN: Hanya mengizinkan role 'admin' untuk masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

// PROSES TAMBAH PRODUK BARU (CRUD - CREATE)
if (isset($_POST['tambah_produk'])) {
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $kategori_id = $_POST['kategori_id'];
    $harga = $_POST['harga'];
    $harga_lama = !empty($_POST['harga_lama']) ? $_POST['harga_lama'] : "NULL";
    $stok = $_POST['stok'];
    $badge_status = mysqli_real_escape_string($koneksi, $_POST['badge_status']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $gambar = mysqli_real_escape_string($koneksi, $_POST['gambar']);

    $query_insert = "INSERT INTO produk (nama_produk, kategori_id, harga, harga_lama, gambar, stok, badge_status, deskripsi, is_favorit) 
                     VALUES ('$nama_produk', $kategori_id, $harga, $harga_lama, '$gambar', $stok, '$badge_status', '$deskripsi', 0)";
    
    if (mysqli_query($koneksi, $query_insert)) {
        echo "<script>alert('✓ Produk baru berhasil ditambahkan ke database!'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal menambahkan produk: " . mysqli_error($koneksi) . "');</script>";
    }
}

// PROSES HAPUS PRODUK (CRUD - DELETE)
if (isset($_GET['hapus_id'])) {
    $id_hapus = $_GET['hapus_id'];
    $query_delete = "DELETE FROM produk WHERE id = $id_hapus";
    
    if (mysqli_query($koneksi, $query_delete)) {
        echo "<script>alert('✓ Produk berhasil dihapus dari sistem!'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal menghapus produk!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsol Admin - Warung Bhakti Karya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F0F0F0] min-h-screen text-gray-800 p-4 sm:p-6 lg:p-8">

    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="bg-white rounded-3xl p-6 shadow-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-600 to-orange-500 flex items-center justify-center text-white shadow-md">
                    <i class="fa-solid fa-user-gear text-xl"></i>
                </div>
                <div>
                    <h1 class="text-base font-extrabold text-gray-900 tracking-tight uppercase leading-none">Konsol Utama Admin</h1>
                    <p class="text-xs text-gray-400 mt-1">Selamat datang kembali, <strong class="text-[#468191]"><?php echo $_SESSION['nama_lengkap']; ?></strong></p>
                </div>
            </div>
            <a href="login.php?action=logout" class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-5 py-3 rounded-xl font-bold transition-all shadow-xs flex items-center gap-1.5">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar Sistem (Logout)
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <div class="bg-white p-6 rounded-3xl shadow-xl border border-gray-100 space-y-4">
                <h2 class="text-xs font-bold uppercase tracking-widest text-[#468191] border-b pb-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-square-plus"></i> Input Komoditas Baru
                </h2>
                <form action="admin.php" method="POST" class="space-y-3.5 text-xs">
                    <div>
                        <label class="block font-bold text-gray-500 mb-1 uppercase tracking-wider">Nama Barang Dagangan *</label>
                        <input type="text" name="nama_produk" required placeholder="Contoh: Minyak Goreng Filma 2L" class="w-full bg-gray-50 p-3 rounded-xl border focus:outline-none focus:border-[#468191] focus:bg-white transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-gray-500 mb-1 uppercase tracking-wider">Kategori *</label>
                            <select name="kategori_id" required class="w-full bg-gray-50 p-3 rounded-xl border focus:outline-none focus:border-[#468191] focus:bg-white transition-all">
                                <option value="1">🌾 Sembako</option>
                                <option value="2">🧃 Minuman</option>
                                <option value="3">🍪 Snack</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-500 mb-1 uppercase tracking-wider">Label Badge *</label>
                            <input type="text" name="badge_status" required placeholder="Contoh: BEST SELLER" class="w-full bg-gray-50 p-3 rounded-xl border focus:outline-none focus:border-[#468191] focus:bg-white transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-gray-500 mb-1 uppercase tracking-wider">Harga Jual (Rp) *</label>
                            <input type="number" name="harga" required placeholder="14000" class="w-full bg-gray-50 p-3 rounded-xl border focus:outline-none focus:border-[#468191] focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block font-bold text-gray-500 mb-1 uppercase tracking-wider">Harga Lama (Coret)</label>
                            <input type="number" name="harga_lama" placeholder="16500" class="w-full bg-gray-50 p-3 rounded-xl border focus:outline-none focus:border-[#468191] focus:bg-white transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1 uppercase tracking-wider">Stok Gudang *</label>
                        <input type="number" name="stok" required placeholder="45" class="w-full bg-gray-50 p-3 rounded-xl border focus:outline-none focus:border-[#468191] focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1 uppercase tracking-wider">URL Gambar Sembako *</label>
                        <input type="text" name="gambar" required placeholder="https://link-gambar.com/produk.jpg" class="w-full bg-gray-50 p-3 rounded-xl border focus:outline-none focus:border-[#468191] focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1 uppercase tracking-wider">Deskripsi Singkat *</label>
                        <textarea name="deskripsi" required rows="3" placeholder="Tulis deskripsi atau volume bersih sembako..." class="w-full bg-gray-50 p-3 rounded-xl border focus:outline-none focus:border-[#468191] focus:bg-white transition-all"></textarea>
                    </div>
                    <button type="submit" name="tambah_produk" class="w-full bg-[#468191] hover:bg-[#2F5B67] text-white font-bold py-3.5 rounded-xl shadow-md transition-all mt-2 uppercase tracking-wide">
                        Simpan Produk ke Toko
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-xl border border-gray-100 space-y-4">
                <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 border-b pb-2 flex items-center justify-between">
                    <span><i class="fa-solid fa-boxes-stacked"></i> Management Manajemen Stok Toko</span>
                    <a href="index.php" target="_blank" class="text-[#468191] hover:underline font-bold uppercase tracking-normal normal-case flex items-center gap-1"> Lihat Web Depan <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></a>
                </h2>

                <div class="overflow-x-auto text-xs">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100 uppercase tracking-wider text-[10px]">
                                <th class="p-3">Produk</th>
                                <th class="p-3">Kategori</th>
                                <th class="p-3 text-right">Harga</th>
                                <th class="p-3 text-center">Stok</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            <?php
                            $query_tampil = mysqli_query($koneksi, "SELECT p.*, k.nama_kategori FROM produk p JOIN kategori k ON p.kategori_id = k.id ORDER BY p.id DESC");
                            if (mysqli_num_rows($query_tampil) === 0) {
                                echo "<tr><td colspan='5' class='p-4 text-center text-gray-400'>Belum ada katalog barang di database.</td></tr>";
                            }
                            while ($row = mysqli_fetch_assoc($query_tampil)) {
                                ?>
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-3 flex items-center gap-3">
                                        <img src="<?php echo $row['gambar']; ?>" class="w-9 h-9 object-cover rounded-lg bg-gray-100 shadow-inner" onerror="this.src='https://images.placeholders.dev/?width=50&height=50&text=Sembako';">
                                        <div>
                                            <p class="font-semibold text-gray-800 line-clamp-1"><?php echo $row['nama_produk']; ?></p>
                                            <span class="bg-amber-50 text-amber-600 text-[8px] font-extrabold px-1.5 py-0.5 rounded-md uppercase tracking-wider"><?php echo $row['badge_status']; ?></span>
                                        </div>
                                    </td>
                                    <td class="p-3 text-gray-500"><?php echo $row['nama_kategori']; ?></td>
                                    <td class="p-3 text-right font-bold text-status-danger">Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                    <td class="p-3 text-center">
                                        <span class="<?php echo ($row['stok'] <= 5) ? 'bg-red-50 text-red-600 px-2 py-1 rounded-lg font-bold' : 'text-gray-700'; ?>">
                                            <?php echo $row['stok']; ?>
                                        </span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <a href="admin.php?hapus_id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus produk <?php echo addslashes($row['nama_produk']); ?> dari toko?')" class="bg-red-50 hover:bg-red-100 text-status-danger font-bold px-3 py-2 rounded-xl transition-all shadow-xs inline-flex items-center justify-center">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</body>
</html>