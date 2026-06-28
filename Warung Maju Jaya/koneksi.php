<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "db_warung_maju_jaya";

$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>