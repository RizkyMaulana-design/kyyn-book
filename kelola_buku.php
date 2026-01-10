<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login dan admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:index.php");
    exit;
}

// LOGIKA HAPUS BUKU
if (isset($_GET['hapus'])) {
    $id_buku = $_GET['hapus'];

    // Ambil nama file gambar dulu untuk dihapus dari folder
    $q_gambar = mysqli_query($koneksi, "SELECT gambar FROM buku WHERE id_buku='$id_buku'");
    $data_gambar = mysqli_fetch_assoc($q_gambar);
    $path_gambar = "img/" . $data_gambar['gambar'];

    // Hapus data dari database
    $hapus = mysqli_query($koneksi, "DELETE FROM buku WHERE id_buku='$id_buku'");

    if ($hapus) {
        // Hapus file fisik gambar jika ada
        if (file_exists($path_gambar) && $data_gambar['gambar'] != "") {
            unlink($path_gambar);
        }
        echo "<script>alert('Buku berhasil dihapus!'); window.location='kelola_buku.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Buku - Admin Kyyn Book</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        /* 1. SETUP TEMA GALAXY (KONSISTEN) */
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

        /* Warna beda dikit buat Admin */

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

        /* 3. CONTAINER UTAMA */
        .container {
            width: 95%;
            max-width: 1400px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }

        /* Header Halaman */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 15px;
        }

        .page-header h3 {
            font-size: 1.5rem;
        }

        /* Tombol Tambah */
        .btn-add {
            background: linear-gradient(90deg, #2ecc71, #27ae60);
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 0.9rem;
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);
            transition: 0.3s;
        }

        .btn-add:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(46, 204, 113, 0.6);
        }

        /* 4. TABEL STYLING (GLASS) */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            color: white;
        }

        thead tr {
            background: rgba(255, 255, 255, 0.1);
            text-align: left;
        }

        th,
        td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            vertical-align: middle;
        }

        th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        /* Gambar Thumbnail */
        .thumb {
            width: 60px;
            height: 85px;
            object-fit: cover;
            border-radius: 5px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: 0.3s;
        }

        .thumb:hover {
            transform: scale(1.5);
            border-color: #fff;
            position: relative;
            z-index: 10;
        }

        /* Tombol Aksi (Edit/Hapus) */
        .btn-action {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: bold;
            text-decoration: none;
            margin-right: 5px;
            transition: 0.3s;
        }

        .btn-edit {
            background: rgba(52, 152, 219, 0.2);
            color: #3498db;
            border: 1px solid #3498db;
        }

        .btn-edit:hover {
            background: #3498db;
            color: white;
        }

        .btn-delete {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        .btn-delete:hover {
            background: #e74c3c;
            color: white;
        }

        /* Stok Badge */
        .stok-badge {
            background: rgba(255, 255, 255, 0.1);
            padding: 5px 10px;
            border-radius: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <nav>
        <h2>Kyyn<span>Admin</span></h2>
        <div class="menu">
            <a href="admin_dashboard.php">🏠 Home</a>
            <a href="kelola_buku.php" style="color: #ffdd59; font-weight:bold;">📚 Kelola Buku</a>
            <a href="transaksi.php">🔄 Transaksi</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="container">

        <div class="page-header">
            <h3>Manajemen Data Buku</h3>
            <a href="tambah_buku.php" class="btn-add">+ Tambah Buku Baru</a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Cover</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id_buku DESC");

                    if (mysqli_num_rows($query) > 0) {
                        while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>

                                <td>
                                    <img src="img/<?php echo $data['gambar']; ?>" class="thumb"
                                        onerror="this.src='https://via.placeholder.com/60x85?text=No+Img'">
                                </td>

                                <td style="font-weight: 600;"><?php echo $data['judul_buku']; ?></td>
                                <td><?php echo $data['pengarang']; ?></td>
                                <td><?php echo $data['penerbit']; ?></td>
                                <td><?php echo $data['tahun_terbit']; ?></td>

                                <td>
                                    <span class="stok-badge"><?php echo $data['stok']; ?></span>
                                </td>

                                <td>
                                    <a href="edit_buku.php?id=<?php echo $data['id_buku']; ?>"
                                        class="btn-action btn-edit">Edit</a>
                                    <a href="kelola_buku.php?hapus=<?php echo $data['id_buku']; ?>"
                                        class="btn-action btn-delete"
                                        onclick="return confirm('Yakin ingin menghapus buku: <?php echo $data['judul_buku']; ?>?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                                <?php
                        }
                    } else {
                        echo "<tr><td colspan='8' style='text-align:center; padding:30px;'>Belum ada data buku. Silakan tambah data.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>