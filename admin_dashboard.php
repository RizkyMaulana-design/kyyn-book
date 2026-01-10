<?php
session_start();
include 'koneksi.php';

// Cek Level Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:index.php");
    exit;
}

// --- LOGIKA HITUNG DATA ---
$q_buku = mysqli_query($koneksi, "SELECT * FROM buku");
$jml_buku = mysqli_num_rows($q_buku);

$q_user = mysqli_query($koneksi, "SELECT * FROM users WHERE role='user'");
$jml_user = mysqli_num_rows($q_user);

$q_pinjam = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE status='dipinjam'");
$jml_pinjam = mysqli_num_rows($q_pinjam);

$q_denda = mysqli_query($koneksi, "SELECT SUM(denda) as total_denda FROM peminjaman");
$d_denda = mysqli_fetch_assoc($q_denda);
$total_denda = $d_denda['total_denda'];
if ($total_denda == NULL)
    $total_denda = 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Kyyn Book</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        /* 1. RESET & FONT */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        /* 2. BACKGROUND TETAP (PARTICLE NETWORK) */
        body {
            background-color: #0b0e14;
            min-height: 100vh;
            color: white;
            overflow-x: hidden;
            margin: 0;
        }

        #network4d {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        /* 3. NAVBAR NEON */
        nav {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 210, 255, 0.3);
            /* Garis Biru Tipis */
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 0 20px rgba(0, 210, 255, 0.2);
            /* Glow Navbar */
        }

        nav h2 {
            font-weight: 800;
            letter-spacing: 1px;
            color: white;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        nav h2 span {
            color: #00d2ff;
            text-shadow: 0 0 15px #00d2ff;
        }

        nav .menu a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            margin-left: 20px;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        nav .menu a:hover {
            color: #00d2ff;
            text-shadow: 0 0 10px #00d2ff;
        }

        .btn-logout {
            background: rgba(255, 65, 108, 0.2);
            border: 1px solid #ff416c;
            padding: 6px 18px;
            border-radius: 20px;
            color: #ff416c !important;
            box-shadow: 0 0 10px rgba(255, 65, 108, 0.3);
        }

        .btn-logout:hover {
            background: #ff416c;
            color: white !important;
            box-shadow: 0 0 20px #ff416c;
        }

        /* 4. CONTAINER */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* WELCOME BOX (Glow Biru Kuat) */
        .welcome-box {
            background: rgba(0, 210, 255, 0.1);
            /* Biru Transparan */
            backdrop-filter: blur(10px);
            border: 2px solid rgba(0, 210, 255, 0.5);
            /* Border Nyala */
            padding: 30px;
            border-radius: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            box-shadow: 0 0 30px rgba(0, 210, 255, 0.2), inset 0 0 20px rgba(0, 210, 255, 0.1);
            /* Efek Pendar */
        }

        .welcome-text h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
            text-shadow: 0 0 10px rgba(0, 210, 255, 0.5);
        }

        .welcome-text p {
            color: #d0f0ff;
            font-size: 0.9rem;
        }

        .date-badge {
            background: rgba(0, 210, 255, 0.2);
            color: #fff;
            padding: 10px 20px;
            border-radius: 10px;
            border: 1px solid #00d2ff;
            font-weight: bold;
            box-shadow: 0 0 15px rgba(0, 210, 255, 0.4);
        }

        /* 5. STATISTIK CARDS (NEON BLOCKS) */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .card {
            padding: 25px;
            border-radius: 20px;
            transition: 0.3s;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .card:hover {
            transform: translateY(-10px) scale(1.02);
        }

        /* --- WARNA-WARNI GLOW IN THE DARK --- */

        /* Kartu Biru */
        .card-blue {
            background: rgba(0, 210, 255, 0.15);
            /* Isi Biru Transparan */
            border: 2px solid #00d2ff;
            /* Garis Tepi Nyala */
            box-shadow: 0 0 25px rgba(0, 210, 255, 0.3);
            /* Pendar Cahaya */
        }

        .card-blue:hover {
            box-shadow: 0 0 50px rgba(0, 210, 255, 0.6);
        }

        .card-blue h1 {
            color: #00d2ff;
            text-shadow: 0 0 15px #00d2ff;
        }

        /* Kartu Ungu */
        .card-purple {
            background: rgba(189, 0, 255, 0.15);
            border: 2px solid #bd00ff;
            box-shadow: 0 0 25px rgba(189, 0, 255, 0.3);
        }

        .card-purple:hover {
            box-shadow: 0 0 50px rgba(189, 0, 255, 0.6);
        }

        .card-purple h1 {
            color: #bd00ff;
            text-shadow: 0 0 15px #bd00ff;
        }

        /* Kartu Oranye */
        .card-orange {
            background: rgba(255, 136, 0, 0.15);
            border: 2px solid #ff8800;
            box-shadow: 0 0 25px rgba(255, 136, 0, 0.3);
        }

        .card-orange:hover {
            box-shadow: 0 0 50px rgba(255, 136, 0, 0.6);
        }

        .card-orange h1 {
            color: #ff8800;
            text-shadow: 0 0 15px #ff8800;
        }

        /* Kartu Hijau */
        .card-green {
            background: rgba(0, 255, 136, 0.15);
            border: 2px solid #00ff88;
            box-shadow: 0 0 25px rgba(0, 255, 136, 0.3);
        }

        .card-green:hover {
            box-shadow: 0 0 50px rgba(0, 255, 136, 0.6);
        }

        .card-green h1 {
            color: #00ff88;
            text-shadow: 0 0 15px #00ff88;
        }


        .card h1 {
            font-size: 3rem;
            margin-bottom: 5px;
        }

        .card p {
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #fff;
            letter-spacing: 1px;
        }

        .icon-bg {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 3.5rem;
            opacity: 0.3;
            color: white;
            /* Ikon lebih terlihat */
        }

        /* 6. AKSES CEPAT (Tombol Nyala) */
        .section-title {
            font-size: 1.3rem;
            margin-bottom: 25px;
            border-left: 5px solid #00d2ff;
            padding-left: 15px;
            text-shadow: 0 0 10px #00d2ff;
        }

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .quick-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            text-decoration: none;
            color: white;
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 120px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.05);
        }

        .quick-btn:hover {
            background: rgba(0, 210, 255, 0.2);
            border-color: #00d2ff;
            color: #fff;
            transform: translateY(-5px);
            box-shadow: 0 0 30px rgba(0, 210, 255, 0.5);
            /* Glow Biru saat hover */
        }

        .quick-icon {
            font-size: 2rem;
            margin-bottom: 10px;
            text-shadow: 0 0 10px white;
        }

        .quick-text {
            font-size: 0.9rem;
            font-weight: 600;
        }

        @media(max-width: 768px) {
            .quick-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .welcome-box {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
        }
    </style>
</head>

<body>

    <canvas id="network4d"></canvas>

    <nav>
        <h2>Kyyn<span>Admin</span></h2>
        <div class="menu">
            <a href="admin_dashboard.php" style="color: #00d2ff; text-shadow: 0 0 10px #00d2ff;">🏠 Dashboard</a>
            <a href="kelola_buku.php">📚 Buku</a>
            <a href="transaksi.php">🔄 Transaksi</a>
            <a href="logout.php"><button class="btn-logout">Logout</button></a>
        </div>
    </nav>

    <div class="container">

        <div class="welcome-box">
            <div class="welcome-text">
                <h1>Selamat Datang, Komandan! 🚀</h1>
                <p>Status Sistem: Online. Semua modul berfungsi normal.</p>
            </div>
            <div class="date-badge">
                <?php echo date('d F Y'); ?>
            </div>
        </div>

        <div class="stats-grid">
            <div class="card card-blue">
                <div class="icon-bg">📚</div>
                <h1><?php echo $jml_buku; ?></h1>
                <p>Koleksi Buku</p>
            </div>

            <div class="card card-purple">
                <div class="icon-bg">👥</div>
                <h1><?php echo $jml_user; ?></h1>
                <p>Total Member</p>
            </div>

            <div class="card card-orange">
                <div class="icon-bg">⚡</div>
                <h1><?php echo $jml_pinjam; ?></h1>
                <p>Sedang Dipinjam</p>
            </div>

            <div class="card card-green">
                <div class="icon-bg">💎</div>
                <h1>Rp <?php echo number_format($total_denda / 1000); ?>k</h1>
                <p>Total Denda</p>
            </div>
        </div>

        <h3 class="section-title">Panel Kontrol</h3>
        <div class="quick-grid">
            <a href="kelola_buku.php" class="quick-btn">
                <span class="quick-icon">📕</span>
                <span class="quick-text">Data Buku</span>
            </a>
            <a href="transaksi.php" class="quick-btn">
                <span class="quick-icon">📝</span>
                <span class="quick-text">Cek Transaksi</span>
            </a>
            <a href="tambah_buku.php" class="quick-btn">
                <span class="quick-icon">➕</span>
                <span class="quick-text">Tambah Buku</span>
            </a>
            <a href="laporan.php" class="quick-btn">
                <span class="quick-icon">📊</span>
                <span class="quick-text">Cetak Laporan</span>
            </a>
        </div>

    </div>

    <script>
        const canvas = document.getElementById('network4d');
        const ctx = canvas.getContext('2d');
        let width, height;
        let particles = [];
        const particleCount = 100;
        const connectionDistance = 150;
        const moveSpeed = 0.5;

        function resize() { width = window.innerWidth; height = window.innerHeight; canvas.width = width; canvas.height = height; }
        window.addEventListener('resize', resize); resize();

        class Particle {
            constructor() {
                this.x = Math.random() * width; this.y = Math.random() * height;
                this.vx = (Math.random() - 0.5) * moveSpeed; this.vy = (Math.random() - 0.5) * moveSpeed;
                this.size = Math.random() * 2 + 1;
            }
            update() {
                this.x += this.vx; this.y += this.vy;
                if (this.x < 0 || this.x > width) this.vx *= -1;
                if (this.y < 0 || this.y > height) this.vy *= -1;
            }
            draw() {
                ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = "rgba(0, 210, 255, 0.8)"; ctx.fill();
            }
        }
        for (let i = 0; i < particleCount; i++) particles.push(new Particle());

        function animate() {
            ctx.clearRect(0, 0, width, height);
            for (let i = 0; i < particles.length; i++) {
                particles[i].update(); particles[i].draw();
                for (let j = i; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x; const dy = particles[i].y - particles[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    if (distance < connectionDistance) {
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(0, 210, 255, ${1 - distance / connectionDistance})`;
                        ctx.lineWidth = 1; ctx.moveTo(particles[i].x, particles[i].y); ctx.lineTo(particles[j].x, particles[j].y); ctx.stroke();
                    }
                }
            } requestAnimationFrame(animate);
        } animate();
    </script>

</body>

</html>