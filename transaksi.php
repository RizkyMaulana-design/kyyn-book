<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login dan admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi - Admin Kyyn Book</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        /* 1. SETUP TEMA GALAXY (Sama dengan halaman lain) */
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
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .page-header h3 {
            font-size: 1.5rem;
            color: #fff;
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

        /* Badge Status Keren */
        .status-badge {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: bold;
            display: inline-block;
        }

        .status-pinjam {
            background: rgba(255, 221, 89, 0.15);
            color: #ffdd59;
            border: 1px solid #ffdd59;
            box-shadow: 0 0 10px rgba(255, 221, 89, 0.2);
        }

        .status-selesai {
            background: rgba(46, 204, 113, 0.15);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }

        /* Tombol Proses Kembali */
        .btn-kembali {
            background: linear-gradient(90deg, #00d2ff, #3a7bd5);
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 0.8rem;
            box-shadow: 0 5px 15px rgba(0, 210, 255, 0.3);
            transition: 0.3s;
            display: inline-block;
        }

        .btn-kembali:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 210, 255, 0.5);
        }

        .text-done {
            color: #aaa;
            font-style: italic;
            font-size: 0.9rem;
        }

        /* Info Denda */
        .denda-text {
            color: #ff4757;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <nav>
        <h2>Kyyn<span>Admin</span></h2>
        <div class="menu">
            <a href="admin_dashboard.php">🏠 Home</a>
            <a href="kelola_buku.php">📚 Kelola Buku</a>
            <a href="transaksi.php" style="color: #ffdd59; font-weight:bold;">🔄 Transaksi</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="container">

        <div class="page-header">
            <h3>Monitoring Sirkulasi Peminjaman</h3>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Judul Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tgl Kembali</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Join tabel: peminjaman, users, buku
                    $query = mysqli_query($koneksi, "SELECT * FROM peminjaman 
                                                     JOIN users ON peminjaman.id_user = users.id_user
                                                     JOIN buku ON peminjaman.id_buku = buku.id_buku
                                                     ORDER BY id_pinjam DESC");

                    if (mysqli_num_rows($query) > 0) {
                        while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>

                                <td style="font-weight: 600; color: #00d2ff;">
                                    <?php echo $data['nama_lengkap']; ?>
                                </td>

                                <td><?php echo $data['judul_buku']; ?></td>

                                <td><?php echo date('d M Y', strtotime($data['tgl_pinjam'])); ?></td>

                                <td style="color: #ffdd59;">
                                    <?php echo date('d M Y', strtotime($data['tgl_kembali_rencana'])); ?>
                                </td>

                                <td>
                                    <?php
                                    if ($data['tgl_kembali_aktual']) {
                                        echo date('d M Y', strtotime($data['tgl_kembali_aktual']));
                                    } else {
                                        echo "-";
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if ($data['denda'] > 0) {
                                        echo "<span class='denda-text'>Rp " . number_format($data['denda']) . "</span>";
                                    } else {
                                        echo "Rp 0";
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php if ($data['status'] == 'dipinjam') { ?>
                                        <span class="status-badge status-pinjam">Dipinjam</span>
                                    <?php } else { ?>
                                        <span class="status-badge status-selesai">Selesai</span>
                                    <?php } ?>
                                </td>

                                <td>
                                    <?php if ($data['status'] == 'dipinjam') { ?>
                                        <a href="proses_kembali.php?id=<?php echo $data['id_pinjam']; ?>&id_buku=<?php echo $data['id_buku']; ?>"
                                            onclick="return confirm('Proses pengembalian buku ini? Stok akan bertambah otomatis.')"
                                            class="btn-kembali">
                                            ✔ Terima
                                        </a>
                                    <?php } else { ?>
                                        <span class="text-done">✔ Tuntas</span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='9' style='text-align:center; padding:30px;'>Belum ada data transaksi.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>