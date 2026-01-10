<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login dan admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:index.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun'];
    $stok = $_POST['stok'];

    // PROSES UPLOAD GAMBAR
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    if ($gambar != "") {
        $nama_baru = date('dmYHis') . "_" . $gambar;
        $path = "img/" . $nama_baru;

        if (move_uploaded_file($tmp, $path)) {
            $query = mysqli_query($koneksi, "INSERT INTO buku (judul_buku, pengarang, penerbit, tahun_terbit, stok, gambar) 
                     VALUES ('$judul', '$pengarang', '$penerbit', '$tahun', '$stok', '$nama_baru')");

            if ($query) {
                echo "<script>alert('Buku Berhasil Ditambahkan!'); window.location='kelola_buku.php';</script>";
            }
        } else {
            echo "<script>alert('Gagal upload gambar!');</script>";
        }
    } else {
        echo "<script>alert('Harap pilih gambar sampul!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku - Admin Kyyn Book</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        /* 1. SETUP TEMA GALAXY */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(-45deg, #0f0c29, #302b63, #24243e);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            color: white;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* 2. NAVBAR ADMIN */
        nav {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        nav h2 {
            font-weight: 800;
            letter-spacing: 1px;
            color: #fff;
        }

        nav h2 span {
            color: #ffdd59;
        }

        nav .menu a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            margin-left: 20px;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        nav .menu a:hover {
            color: #fff;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        .btn-logout {
            background: #e74c3c;
            padding: 5px 15px;
            border-radius: 20px;
            color: white !important;
        }

        /* 3. CONTAINER FORM TENGAH */
        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .form-box {
            width: 100%;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h2 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .form-header p {
            color: #aaa;
            font-size: 0.9rem;
        }

        /* 4. FORM STYLING */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #ccc;
            font-size: 0.9rem;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 12px 15px;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: white;
            font-size: 1rem;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            background: rgba(0, 0, 0, 0.4);
            border-color: #2ecc71;
            box-shadow: 0 0 10px rgba(46, 204, 113, 0.3);
        }

        /* Custom File Upload */
        .file-upload-wrapper {
            position: relative;
            width: 100%;
            height: 50px;
            border: 2px dashed rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
        }

        .file-upload-wrapper:hover {
            border-color: #2ecc71;
            background: rgba(46, 204, 113, 0.1);
        }

        input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-text {
            color: #aaa;
            font-size: 0.9rem;
            pointer-events: none;
        }

        /* Tombol Aksi */
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-save {
            flex: 1;
            padding: 12px;
            background: linear-gradient(90deg, #2ecc71, #27ae60);
            border: none;
            border-radius: 30px;
            color: white;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }

        .btn-save:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(46, 204, 113, 0.5);
        }

        .btn-cancel {
            padding: 12px 25px;
            background: transparent;
            border: 2px solid #e74c3c;
            border-radius: 30px;
            color: #e74c3c;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            transition: 0.3s;
        }

        .btn-cancel:hover {
            background: #e74c3c;
            color: white;
        }
    </style>
</head>

<body>

    <nav>
        <h2>Kyyn<span>Admin</span></h2>
        <div class="menu">
            <a href="admin_dashboard.php">🏠 Home</a>
            <a href="kelola_buku.php" style="color: #ffdd59;">📚 Kelola Buku</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="main-content">
        <div class="form-box">
            <div class="form-header">
                <h2>Tambah Koleksi Buku</h2>
                <p>Masukkan detail buku baru dengan lengkap.</p>
            </div>

            <form method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label>Judul Buku</label>
                    <input type="text" name="judul" placeholder="Contoh: Belajar PHP Dasar" required>
                </div>

                <div class="form-group">
                    <label>Pengarang</label>
                    <input type="text" name="pengarang" placeholder="Nama Penulis" required>
                </div>

                <div class="form-group" style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label>Penerbit</label>
                        <input type="text" name="penerbit" placeholder="Nama Penerbit" required>
                    </div>
                    <div style="flex: 1;">
                        <label>Tahun Terbit</label>
                        <input type="number" name="tahun" placeholder="2024" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Jumlah Stok</label>
                    <input type="number" name="stok" placeholder="Jumlah buku tersedia" required>
                </div>

                <div class="form-group">
                    <label>Upload Sampul Buku</label>
                    <div class="file-upload-wrapper">
                        <input type="file" name="gambar" required
                            onchange="this.nextElementSibling.innerText = this.files[0].name">
                        <span class="file-upload-text">Klik untuk pilih gambar (JPG/PNG)</span>
                    </div>
                </div>

                <div class="btn-group">
                    <a href="kelola_buku.php" class="btn-cancel">Batal</a>
                    <button type="submit" name="simpan" class="btn-save">SIMPAN DATA</button>
                </div>

            </form>
        </div>
    </div>

</body>

</html>