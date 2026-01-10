<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['role'])) {
    header("location:index.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// --- 1. LOGIKA UPDATE FOTO PROFIL (BARU) ---
if (isset($_POST['simpan_foto'])) {
    $foto_nama = $_FILES['foto_baru']['name'];
    $foto_tmp = $_FILES['foto_baru']['tmp_name'];

    // Cek apakah ada file yang diupload
    if ($foto_nama != "") {
        // Buat nama file unik biar ga bentrok
        $nama_file_baru = date('dmYHis') . "_" . $foto_nama;
        $folder_tujuan = "img/" . $nama_file_baru;

        // Coba upload
        if (move_uploaded_file($foto_tmp, $folder_tujuan)) {
            // Update database
            $query_foto = mysqli_query($koneksi, "UPDATE users SET foto_profil='$nama_file_baru' WHERE id_user='$id_user'");

            if ($query_foto) {
                echo "<script>alert('Foto Profil Berhasil Diganti!'); window.location='profil_saya.php';</script>";
            } else {
                echo "<script>alert('Gagal update database!');</script>";
            }
        } else {
            echo "<script>alert('Gagal upload gambar. Pastikan folder img ada!');</script>";
        }
    }
}

// --- 2. LOGIKA UPDATE DATA DIRI ---
if (isset($_POST['update_data'])) {
    $nama = $_POST['nama'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];

    $query_update = mysqli_query($koneksi, "UPDATE users SET nama_lengkap='$nama', no_telp='$no_telp', alamat='$alamat' WHERE id_user='$id_user'");

    if ($query_update) {
        $_SESSION['nama'] = $nama; // Update nama di sesi juga
        echo "<script>alert('Data diri berhasil diperbarui!'); window.location='profil_saya.php';</script>";
    }
}

// AMBIL DATA USER TERBARU
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id_user'");
$data = mysqli_fetch_assoc($query);

// Cek Foto: Kalau kosong/default, pakai inisial huruf. Kalau ada, pakai gambarnya.
$pakai_foto_asli = false;
if ($data['foto_profil'] != 'default.jpg' && $data['foto_profil'] != '') {
    $pakai_foto_asli = true;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Kyyn Book</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        /* TEMA GALAXY */
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

        /* NAVBAR */
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

        /* LAYOUT STACKED */
        .container {
            width: 95%;
            max-width: 800px;
            margin: 40px auto;
        }

        /* HEADER FOTO */
        .profile-header-glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px 20px 0 0;
            padding: 40px 20px;
            text-align: center;
            box-shadow: 0 -10px 25px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 2;
        }

        /* Lingkaran Avatar */
        .avatar-circle {
            width: 160px;
            height: 160px;
            margin: 0 auto 20px;
            border-radius: 50%;
            overflow: hidden;
            /* Agar gambar terpotong bulat */
            background: linear-gradient(45deg, #00d2ff, #3a7bd5);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 5px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 30px rgba(0, 210, 255, 0.5);
        }

        /* Foto Asli (Img) */
        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Inisial Huruf (Text) */
        .avatar-text {
            font-size: 4rem;
            font-weight: 800;
            color: white;
        }

        .username-title {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .role-text {
            color: #00d2ff;
            font-weight: 600;
            letter-spacing: 1px;
        }

        /* INPUT FILE CUSTOM */
        .file-upload-wrapper {
            margin-top: 15px;
        }

        input[type="file"] {
            display: none;
        }

        /* Sembunyikan input asli yg jelek */

        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 8px 20px;
            cursor: pointer;
            border-radius: 30px;
            background: rgba(0, 0, 0, 0.3);
            color: #ddd;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .custom-file-upload:hover {
            background: #00d2ff;
            color: black;
            border-color: #00d2ff;
        }

        /* Tombol Upload Kecil */
        .btn-upload-kecil {
            background: #2ecc71;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
            margin-left: 10px;
        }

        /* FORM DATA DIRI */
        .form-container-glass {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-top: none;
            border-radius: 0 0 20px 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            position: relative;
            top: -2px;
            z-index: 1;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #aaa;
            font-size: 0.9rem;
        }

        .input-text,
        textarea {
            width: 100%;
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: white;
            font-size: 1rem;
            outline: none;
            transition: 0.3s;
        }

        .input-text:focus,
        textarea:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #00d2ff;
            box-shadow: 0 0 15px rgba(0, 210, 255, 0.3);
        }

        .btn-save-big {
            width: 100%;
            padding: 15px;
            background: linear-gradient(90deg, #00d2ff, #3a7bd5);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
            cursor: pointer;
            margin-top: 20px;
            box-shadow: 0 5px 20px rgba(0, 210, 255, 0.4);
            transition: 0.3s;
        }

        .btn-save-big:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 210, 255, 0.6);
        }
    </style>
</head>

<body>

    <nav>
        <h2>Kyyn<span style="color:#00d2ff">Book</span></h2>
        <div class="menu">
            <a href="user_dashboard.php">📚 Katalog</a>
            <a href="riwayat_pinjam.php">🕒 Riwayat</a>
            <a href="profil_saya.php" style="color:#00d2ff; text-shadow:0 0 10px rgba(0,210,255,0.5);">👤 Profil</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="container">

        <div class="profile-header-glass">
            <div class="avatar-circle">
                <?php if ($pakai_foto_asli) { ?>
                    <img src="img/<?php echo $data['foto_profil']; ?>" class="avatar-img">
                <?php } else { ?>
                    <span class="avatar-text"><?php echo strtoupper(substr($data['nama_lengkap'], 0, 1)); ?></span>
                <?php } ?>
            </div>

            <h1 class="username-title"><?php echo $data['nama_lengkap']; ?></h1>
            <p class="role-text">@<?php echo $data['username']; ?></p>

            <form method="POST" enctype="multipart/form-data" class="file-upload-wrapper">
                <label for="file-upload" class="custom-file-upload">
                    📷 Pilih Foto Baru
                </label>
                <input id="file-upload" type="file" name="foto_baru" required
                    onchange="this.form.submit_btn.style.display='inline-block'">

                <button type="submit" name="simpan_foto" id="submit_btn" class="btn-upload-kecil" style="display:none;">
                    Upload ✔
                </button>
            </form>
        </div>

        <div class="form-container-glass">
            <form method="POST">
                <div class="form-group">
                    <label>Username (ID Login)</label>
                    <input type="text" class="input-text" value="<?php echo $data['username']; ?>" disabled
                        style="cursor: not-allowed; opacity: 0.6;">
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="input-text" value="<?php echo $data['nama_lengkap']; ?>"
                        required>
                </div>

                <div class="form-group">
                    <label>No. Telepon / WhatsApp</label>
                    <input type="number" name="no_telp" class="input-text" value="<?php echo $data['no_telp']; ?>"
                        placeholder="08xxxxxxxx" required>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea name="alamat" required><?php echo $data['alamat']; ?></textarea>
                </div>

                <button type="submit" name="update_data" class="btn-save-big">SIMPAN DATA DIRI</button>
            </form>
        </div>

    </div>

</body>

</html>