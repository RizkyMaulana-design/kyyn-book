<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("location:index.php");
    exit;
}

$id_buku = $_GET['id'];

// Ambil data buku berdasarkan ID
$query = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku='$id_buku'");
$data = mysqli_fetch_assoc($query);

// Hitung Tanggal
$tgl_pinjam = date('d-m-Y');
$tgl_kembali = date('d-m-Y', strtotime('+7 days'));
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Transaksi - Kyyn Book</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
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

        /* 2. KARTU TRANSAKSI GLASS */
        .transaksi-box {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            display: flex;
            gap: 30px;
            align-items: center;
        }

        /* 3. LAYOUT GAMBAR */
        .book-cover {
            width: 250px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        /* 4. DETAIL TRANSAKSI */
        .details {
            flex: 1;
        }

        h2 {
            margin-bottom: 5px;
            color: #fff;
            font-size: 1.8rem;
        }

        .author {
            color: #ccc;
            margin-bottom: 20px;
            font-style: italic;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .label {
            color: #aaa;
        }

        .value {
            font-weight: bold;
            color: #00d2ff;
        }

        /* 5. TOMBOL AKSI */
        .btn-group {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }

        .btn-confirm {
            flex: 1;
            padding: 15px;
            background: #00d2ff;
            color: #000;
            text-align: center;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 0 15px rgba(0, 210, 255, 0.5);
            transition: 0.3s;
        }

        .btn-confirm:hover {
            transform: scale(1.05);
            background: #fff;
        }

        .btn-cancel {
            padding: 15px 30px;
            border: 2px solid #e74c3c;
            color: #e74c3c;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-cancel:hover {
            background: #e74c3c;
            color: white;
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .transaksi-box {
                flex-direction: column;
                text-align: center;
            }

            .book-cover {
                width: 180px;
            }

            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <div class="transaksi-box">
        <img src="img/<?php echo $data['gambar']; ?>" class="book-cover"
            onerror="this.src='https://via.placeholder.com/250x350?text=No+Cover'">

        <div class="details">
            <h2>
                <?php echo $data['judul_buku']; ?>
            </h2>
            <p class="author">Karya:
                <?php echo $data['pengarang']; ?>
            </p>

            <div class="info-row">
                <span class="label">Penerbit</span>

                <span class="value">
                    <?php echo $data['penerbit']; ?> (
                    <?php echo $data['tahun_terbit']; ?>)
                </span>
            </div>
            <div class="info-row">
                <span class="label">Tanggal Pinjam</span>
                <span class="value">
                    <?php echo $tgl_pinjam; ?>
                </span>
            </div>
            <div class="info-row">
                <span class="label">Wajib Kembali</span>
                <span class="value" style="color: #ffdd59;">
                    <?php echo $tgl_kembali; ?>
                </span>
            </div>
            <div class="info-row">
                <span class="label">Durasi</span>
                <span class="value">7 Hari</span>
            </div>
            <div class="info-row" style="border: none;">
                <span class="label">Denda Keterlambatan</span>
                <span class="value" style="color: #ff4757;">Rp 1.000 / Hari</span>
            </div>

            <div class="btn-group">
                <a href="user_dashboard.php" class="btn-cancel">Batal</a>
                <a href="pinjam_buku.php?id=<?php echo $data['id_buku']; ?>" class="btn-confirm">KONFIRMASI
                    PEMINJAMAN</a>
            </div>
        </div>
    </div>

</body>

</html>