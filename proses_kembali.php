<?php
session_start();
include 'koneksi.php';

if ($_SESSION['role'] != 'admin') {
    header("location:index.php");
}

$id_pinjam = $_GET['id'];
$id_buku = $_GET['id_buku'];

// 1. Ambil data tanggal rencana kembali
$query = mysqli_query($koneksi, "SELECT tgl_kembali_rencana FROM peminjaman WHERE id_pinjam='$id_pinjam'");
$data = mysqli_fetch_assoc($query);

$tgl_rencana = $data['tgl_kembali_rencana'];
$tgl_sekarang = date('Y-m-d'); // Tanggal hari ini (saat dikembalikan)

// 2. Hitung Denda
$denda = 0;
// Jika tanggal sekarang lebih besar dari tanggal rencana
if ($tgl_sekarang > $tgl_rencana) {
    $selisih_hari = (strtotime($tgl_sekarang) - strtotime($tgl_rencana)) / (60 * 60 * 24);
    $denda = $selisih_hari * 1000; // Denda Rp 1.000 per hari
}

// 3. Update Tabel Peminjaman (Isi tgl kembali aktual, denda, ubah status)
$update_pinjam = mysqli_query($koneksi, "UPDATE peminjaman SET 
                 tgl_kembali_aktual='$tgl_sekarang', 
                 denda='$denda', 
                 status='dikembalikan' 
                 WHERE id_pinjam='$id_pinjam'");

// 4. Update Stok Buku (Kembalikan stok + 1)
$update_stok = mysqli_query($koneksi, "UPDATE buku SET stok = stok + 1 WHERE id_buku='$id_buku'");

if ($update_pinjam && $update_stok) {
    if ($denda > 0) {
        echo "<script>alert('Buku dikembalikan. TERLAMBAT! Denda: Rp $denda'); window.location='transaksi.php';</script>";
    } else {
        echo "<script>alert('Buku berhasil dikembalikan. Tidak ada denda.'); window.location='transaksi.php';</script>";
    }
} else {
    echo "Gagal memproses.";
}
?>