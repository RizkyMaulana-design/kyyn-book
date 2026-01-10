<?php
session_start();
include 'koneksi.php';

// Cek login user
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("location:index.php");
    exit;
}

$id_user = $_SESSION['id_user'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman - Kyyn Book</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        /* 1. SETUP TEMA GALAXY (Sama seperti Dashboard) */
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

        /* 2. NAVBAR */
        nav {
            background: rgba(0, 0, 0, 0.4);
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

        /* 3. CONTAINER GLASS */
        .container {
            width: 95%;
            max-width: 1400px;
            margin: 40px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        }

        h2.title {
            margin-bottom: 20px;
            border-left: 5px solid #00d2ff;
            padding-left: 15px;
        }

        /* 4. TABEL TRANSPARAN KEREN */
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
            background: rgba(0, 210, 255, 0.2);
            text-align: left;
        }

        th,
        td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        /* Efek Hover Baris */
        tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        /* Gambar Kecil */
        .thumb-img {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* 5. BADGE STATUS */
        .status-badge {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: bold;
            display: inline-block;
        }

        .status-pinjam {
            background: rgba(255, 221, 89, 0.2);
            color: #ffdd59;
            border: 1px solid #ffdd59;
            box-shadow: 0 0 10px rgba(255, 221, 89, 0.2);
        }

        .status-selesai {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid #2ecc71;
            box-shadow: 0 0 10px rgba(46, 204, 113, 0.2);
        }

        /* Tombol Kembali */
        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            color: #ccc;
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .btn-back:hover {
            color: white;
            transform: translateX(-5px);
        }
    </style>
</head>

<body>

    <nav>
        <h2>Kyyn<span style="color:#00d2ff">Book</span></h2>
        <div class="menu">
            <a href="user_dashboard.php">📚 Katalog</a>
            <a href="riwayat_pinjam.php" style="color:#00d2ff; text-shadow:0 0 10px rgba(0,210,255,0.5);">🕒 Riwayat</a>
            <a href="profil_saya.php">👤 Profil</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="container">
        <a href="user_dashboard.php" class="btn-back">← Kembali ke Katalog</a>
        <h2 class="title">Riwayat Peminjaman Saya</h2>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Cover</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Wajib Kembali</th>
                        <th>Tanggal Kembali</th>
                        <th>Denda</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Join tabel agar bisa ambil Gambar & Judul
                    $query = mysqli_query($koneksi, "SELECT * FROM peminjaman 
                                                     JOIN buku ON peminjaman.id_buku = buku.id_buku 
                                                     WHERE peminjaman.id_user = '$id_user' 
                                                     ORDER BY id_pinjam DESC");

                    if (mysqli_num_rows($query) > 0) {
                        while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>

                                <td>
                                    <img src="img/<?php echo $data['gambar']; ?>" class="thumb-img"
                                        onerror="this.src='https://via.placeholder.com/50x70?text=?'">
                                </td>

                                <td style="font-weight: 600; color: #eee;"><?php echo $data['judul_buku']; ?></td>
                                <td><?php echo date('d M Y', strtotime($data['tgl_pinjam'])); ?></td>
                                <td style="color: #ffdd59;">
                                    <?php echo date('d M Y', strtotime($data['tgl_kembali_rencana'])); ?>
                                </td>

                                <td>
                                    <?php
                                    if ($data['tgl_kembali_aktual'] == NULL) {
                                        echo "-";
                                    } else {
                                        echo date('d M Y', strtotime($data['tgl_kembali_aktual']));
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php if ($data['denda'] > 0) { ?>
                                        <span style="color:#ff4757; font-weight:bold;">Rp
                                            <?php echo number_format($data['denda']); ?></span>
                                    <?php } else {
                                        echo "Rp 0";
                                    } ?>
                                </td>

                                <td>
                                    <?php
                                    if ($data['status'] == 'dipinjam') {
                                        echo "<span class='status-badge status-pinjam'>Sedang Dipinjam</span>";
                                    } else {
                                        echo "<span class='status-badge status-selesai'>Dikembalikan</span>";
                                    }
                                    ?>
                                </td>
                            </tr>
                                <?php
                        }
                    } else {
                        echo "<tr><td colspan='8' style='text-align:center; padding:30px;'>Belum ada riwayat peminjaman.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>