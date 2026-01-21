<?php
session_start();
include 'koneksi.php';

// Menangkap data yang dikirim dari form
$username = $_POST['username'];
$password = $_POST['password'];

// Menyeleksi data admin dengan username dan password yang sesuai
$data = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' AND password='$password'");

// Menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($data);

if ($cek > 0) {
    // Ambil data user
    $row = mysqli_fetch_assoc($data);

    $_SESSION['username'] = $username;
    $_SESSION['status'] = "login";

    // Cek level/role (jika ada) atau langsung ke dashboard
    // Asumsi: Kalau berhasil login langsung ke Admin Dashboard
    header("location:admin_dashboard.php");
} else {
    // Kalau gagal, balikin ke halaman login
    header("location:index.php?pesan=gagal");
}
?>