<?php
session_start();
include 'koneksi.php';

// Cek Admin
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
    <title>Laporan Perpustakaan - Kyyn Book</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        /* --- TAMPILAN LAYAR (GALAXY THEME) --- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #050505;
            color: white;
            min-height: 100vh;
        }

        #starfield {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
        }

        .header-laporan {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 20px;
        }

        .header-laporan h2 {
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .header-laporan span {
            color: #00d2ff;
        }

        .header-laporan p {
            color: #aaa;
            font-size: 0.9rem;
        }

        /* TABEL */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: left;
            font-size: 0.9rem;
        }

        th {
            background: rgba(0, 210, 255, 0.1);
            color: #00d2ff;
            text-transform: uppercase;
        }

        /* TOMBOL AKSI (Akan hilang saat diprint) */
        .btn-area {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .btn-print {
            background: linear-gradient(90deg, #00d2ff, #3a7bd5);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 0 15px rgba(0, 210, 255, 0.4);
        }

        .btn-back {
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 0.9rem;
        }

        /* TANDA TANGAN (Hanya muncul saat print) */
        .signature-section {
            display: none;
            margin-top: 50px;
            text-align: right;
        }

        /* --- MODE CETAK (PRINT) --- */
        @media print {

            #starfield,
            .btn-area {
                display: none !important;
            }

            /* Sembunyikan Bintang & Tombol */

            body {
                background-color: white !important;
                color: black !important;
                -webkit-print-color-adjust: exact;
            }

            .container {
                box-shadow: none;
                border: none;
                background: none;
                margin: 0;
                width: 100%;
                max-width: 100%;
            }

            th {
                background-color: #ddd !important;
                color: black !important;
                border: 1px solid black !important;
            }

            td {
                border: 1px solid black !important;
                color: black !important;
            }

            .header-laporan h2 span {
                color: black !important;
            }

            .signature-section {
                display: block;
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    <canvas id="starfield"></canvas>

    <div class="container">

        <div class="btn-area">
            <a href="admin_dashboard.php" class="btn-back">← Kembali ke Dashboard</a>
            <button onclick="window.print()" class="btn-print">🖨️ Cetak / Simpan PDF</button>
        </div>

        <div class="header-laporan">
            <h2>Laporan <span>KyynBook</span></h2>
            <p>Data Riwayat Peminjaman & Pengembalian Buku</p>
            <p>Dicetak pada: <?php echo date('d F Y'); ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Denda</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($koneksi, "SELECT * FROM peminjaman 
                                                 JOIN users ON peminjaman.id_user = users.id_user
                                                 JOIN buku ON peminjaman.id_buku = buku.id_buku
                                                 ORDER BY id_pinjam DESC");
                while ($data = mysqli_fetch_assoc($query)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $data['nama_lengkap']; ?></td>
                        <td><?php echo $data['judul_buku']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($data['tgl_pinjam'])); ?></td>
                        <td>
                            <?php
                            if ($data['tgl_kembali_aktual'])
                                echo date('d/m/Y', strtotime($data['tgl_kembali_aktual']));
                            else
                                echo "-";
                            ?>
                        </td>
                        <td>
                            <?php echo ($data['status'] == 'dipinjam') ? 'Sedang Dipinjam' : 'Selesai'; ?>
                        </td>
                        <td>
                            Rp <?php echo number_format($data['denda']); ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="signature-section" style="color: black;">
            <p>Bekasi, <?php echo date('d F Y'); ?></p>
            <br><br><br>
            <p><strong>( Admin Perpustakaan )</strong></p>
        </div>

    </div>

    <script>
        const canvas = document.getElementById('starfield');
        const ctx = canvas.getContext('2d');
        let width, height, stars = []; const numStars = 400; const speed = 2;
        function resize() { width = window.innerWidth; height = window.innerHeight; canvas.width = width; canvas.height = height; }
        window.addEventListener('resize', resize); resize();
        function createStar() { return { x: Math.random() * width - width / 2, y: Math.random() * height - height / 2, z: Math.random() * width }; }
        for (let i = 0; i < numStars; i++) stars.push(createStar());
        function animate() {
            ctx.fillStyle = "black"; ctx.fillRect(0, 0, width, height); ctx.fillStyle = "white";
            for (let i = 0; i < numStars; i++) {
                let s = stars[i]; s.z -= speed; if (s.z <= 0) { stars[i] = createStar(); stars[i].z = width; s = stars[i]; }
                const x = (s.x / s.z) * (width / 2) + (width / 2); const y = (s.y / s.z) * (height / 2) + (height / 2);
                const size = (1 - s.z / width) * 2;
                if (x >= 0 && x < width && y >= 0 && y < height) { ctx.beginPath(); ctx.arc(x, y, size, 0, Math.PI * 2); ctx.fill(); }
            } requestAnimationFrame(animate);
        } animate();
    </script>
</body>

</html>