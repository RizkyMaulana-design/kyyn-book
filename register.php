<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $role = 'user'; // Default role otomatis 'user'

    // 1. Cek apakah username sudah ada biar tidak duplikat
    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");

    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>alert('Username sudah terpakai, cari yang lain!');</script>";
    } else {
        // 2. Masukkan data ke database
        $query = mysqli_query($koneksi, "INSERT INTO users (nama_lengkap, username, password, role, alamat, no_telp) 
                 VALUES ('$nama', '$username', '$password', '$role', '$alamat', '$no_telp')");

        if ($query) {
            echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Gagal Mendaftar!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Daftar Akun Baru - Kyyn Book</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 50px;
        }

        form {
            width: 300px;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }

        input,
        textarea {
            width: 100%;
            margin-bottom: 10px;
            padding: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: blue;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: darkblue;
        }
    </style>
</head>

<body>
    <h2 align="center">Daftar Akun Peminjam</h2>
    <form method="POST">
        <label>Nama Lengkap:</label>
        <input type="text" name="nama" required placeholder="Contoh: Budi Santoso">

        <label>Username:</label>
        <input type="text" name="username" required placeholder="Untuk login nanti">

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Alamat:</label>
        <textarea name="alamat" required placeholder="Alamat lengkap"></textarea>

        <label>No. Telepon:</label>
        <input type="text" name="no_telp" required placeholder="08xxxx">

        <button type="submit" name="register">Daftar Sekarang</button>
        <br><br>
        <center><a href="index.php">Sudah punya akun? Login</a></center>
    </form>
</body>

</html>