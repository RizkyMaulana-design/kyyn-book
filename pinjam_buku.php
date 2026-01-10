<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'user') {
    header("location:index.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$id_buku = $_GET['id'];

// 1. Ambil data buku untuk cek stok
$cek_buku = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku='$id_buku'");
$data_buku = mysqli_fetch_assoc($cek_buku);

if ($data_buku['stok'] > 0) {
    // Tentukan Tanggal
    $tgl_pinjam = date('Y-m-d');
    $tgl_kembali_rencana = date('Y-m-d', strtotime('+7 days')); // Pinjam 7 hari

    // 2. Kurangi Stok Buku
    $kurang_stok = mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id_buku='$id_buku'");

    // 3. Masukkan ke Tabel Peminjaman
    $insert_pinjam = mysqli_query($koneksi, "INSERT INTO peminjaman 
        (id_user, id_buku, tgl_pinjam, tgl_kembali_rencana, status) 
        VALUES ('$id_user', '$id_buku', '$tgl_pinjam', '$tgl_kembali_rencana', 'dipinjam')");

    if ($insert_pinjam) {
        // Ubah pesan alertnya jadi lebih simpel karena user sudah lihat tanggal di menu transaksi
        echo "<script>alert('Transaksi Berhasil! Buku sudah masuk ke Riwayat Peminjaman.'); window.location='riwayat_pinjam.php';</script>";
    }

} else {
    echo "<script>alert('Stok buku habis!'); window.location='user_dashboard.php';</script>";
}
?>