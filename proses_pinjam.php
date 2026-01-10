<?php
// ... koneksi database ...
$id_buku = $_POST['id_buku'];

// 1. Cek Stok Dulu
$cek_stok = mysqli_query($koneksi, "SELECT stok FROM buku WHERE id_buku='$id_buku'");
$data_buku = mysqli_fetch_assoc($cek_stok);

if ($data_buku['stok'] > 0) {
    // 2. Kurangi Stok
    mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id_buku='$id_buku'");

    // 3. Masukkan ke Tabel Peminjaman
    mysqli_query($koneksi, "INSERT INTO peminjaman VALUES (...)");

    echo "Berhasil pinjam!";
} else {
    echo "Stok buku habis!";
}
?>