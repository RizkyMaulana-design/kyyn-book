<?php
session_start();
include 'koneksi.php';

// Cek apakah user login
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("location:index.php");
    exit;
}

// LOGIKA PENCARIAN
$keyword = "";
if (isset($_GET['cari'])) {
    $keyword = $_GET['cari'];
    $query_str = "SELECT * FROM buku WHERE judul_buku LIKE '%$keyword%' OR pengarang LIKE '%$keyword%' ORDER BY id_buku DESC";
} else {
    $query_str = "SELECT * FROM buku ORDER BY id_buku DESC";
}
$query = mysqli_query($koneksi, $query_str);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Kyyn Book</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        /* 1. RESET & BASIC */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(-45deg, #0f0c29, #302b63, #24243e);
            /* Tema Dark Purple Galaxy */
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

        /* 2. NAVBAR (FULL WIDTH) */
        nav {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px 40px;
            /* Padding samping diperkecil */
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

        /* 3. CONTAINER MENYEBAB KE SAMPING (FULL WIDTH) */
        .container {
            /* PERUBAHAN UTAMA DISINI */
            width: 95%;
            /* Mengambil 95% lebar layar */
            max-width: 1800px;
            /* Batas maksimal diperbesar */
            margin: 30px auto;
            padding: 0 10px;
        }

        /* Header Pencarian */
        .header-box {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .search-container {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .input-search {
            width: 50%;
            /* Lebar search bar */
            padding: 12px 20px;
            border-radius: 30px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 1rem;
            outline: none;
            transition: 0.3s;
        }

        .input-search:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: #00d2ff;
            box-shadow: 0 0 15px rgba(0, 210, 255, 0.5);
        }

        .btn-cari {
            padding: 10px 25px;
            border-radius: 30px;
            border: none;
            background: linear-gradient(90deg, #00d2ff 0%, #3a7bd5 100%);
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-cari:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 210, 255, 0.6);
        }

        /* 4. GRID SYSTEM RAPAT & PENUH */
        .book-grid {
            display: grid;
            /* Kartu akan otomatis mengisi ruang selebar mungkin */
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            /* Jarak antar kartu */
        }

        /* 5. KARTU BUKU */
        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            overflow: hidden;
            transition: 0.3s;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
        }

        .card-img {
            width: 100%;
            height: 320px;
            /* Tinggi gambar konsisten */
            object-fit: cover;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .card-body {
            padding: 15px;
            text-align: center;
            flex-grow: 1;
            /* Agar tombol selalu di bawah rata */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 5px;
            min-height: 45px;
            /* Tinggi minimal judul */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-author {
            color: #aaa;
            font-size: 0.8rem;
            margin-bottom: 10px;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 0.7rem;
            font-weight: bold;
            margin-bottom: 10px;
            align-self: center;
        }

        .stok-ada {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }

        .stok-habis {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        .btn-pinjam {
            display: block;
            width: 100%;
            padding: 10px;
            background: transparent;
            border: 2px solid #00d2ff;
            color: #00d2ff;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.8rem;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
            margin-top: auto;
            /* Dorong tombol ke paling bawah */
        }

        .btn-pinjam:hover {
            background: #00d2ff;
            color: #000;
            box-shadow: 0 0 15px #00d2ff;
        }

        .btn-disabled {
            border-color: #555;
            color: #555;
            cursor: not-allowed;
        }
    </style>
</head>

<body>

    <nav>
        <h2>Kyyn<span style="color:#00d2ff">Book</span></h2>
        <div class="menu">
            <a href="user_dashboard.php">📚 Katalog</a>
            <a href="riwayat_pinjam.php">🕒 Riwayat</a>
            <a href="profil_saya.php">👤 Profil</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="header-box">
            <h2 style="margin-bottom: 10px;">Temukan Buku Favoritmu</h2>

            <form action="" method="GET" class="search-container">
                <input type="text" name="cari" class="input-search" placeholder="Cari judul buku..."
                    value="<?php echo $keyword; ?>">
                <button type="submit" class="btn-cari">Cari</button>
                <?php if ($keyword != "") { ?>
                    <a href="user_dashboard.php" style="padding: 10px; color: white;">Reset</a>
                <?php } ?>
            </form>
        </div>

        <div class="book-grid">
            <?php
            if (mysqli_num_rows($query) > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
                    ?>

                    <div class="card">
                        <img src="img/<?php echo $data['gambar']; ?>" alt="Cover" class="card-img"
                            onerror="this.src='https://via.placeholder.com/200x300/000000/FFFFFF?text=No+Image'">

                        <div class="card-body">
                            <div>
                                <div class="card-title"><?php echo $data['judul_buku']; ?></div>
                                <div class="card-author"><?php echo $data['pengarang']; ?></div>

                                <?php if ($data['stok'] > 0) { ?>
                                    <span class="badge stok-ada">Stok: <?php echo $data['stok']; ?></span>
                                <?php } else { ?>
                                    <span class="badge stok-habis">Habis</span>
                                <?php } ?>
                            </div>

                            <?php if ($data['stok'] > 0) { ?>
                                <a href="transaksi_user.php?id=<?php echo $data['id_buku']; ?>" class="btn-pinjam">
                                    PINJAM
                                </a>
                            <?php } else { ?>
                                <button class="btn-pinjam btn-disabled" disabled>KOSONG</button>
                            <?php } ?>
                        </div>
                    </div>

                    <?php
                }
            } else {
                echo "<h3 style='grid-column: 1/-1; text-align: center; margin-top: 50px;'>Buku tidak ditemukan :(</h3>";
            }
            ?>
        </div>
    </div>

</body>

</html>