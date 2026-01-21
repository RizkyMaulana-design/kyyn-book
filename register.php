<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp']);
    $role = 'user'; // Default role otomatis 'user'

    // 1. Cek apakah username sudah ada
    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");

    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>alert('Username sudah terpakai, silakan cari yang lain!');</script>";
    } else {
        // 2. Masukkan data ke database
        // Opsional: Gunakan password_hash($password, PASSWORD_DEFAULT) jika ingin aman
        $query = mysqli_query($koneksi, "INSERT INTO users (nama_lengkap, username, password, role, alamat, no_telp) 
                 VALUES ('$nama', '$username', '$password', '$role', '$alamat', '$no_telp')");

        if ($query) {
            echo "<script>
                    alert('Pendaftaran Berhasil! Silakan Login.'); 
                    window.location='index.php';
                  </script>";
        } else {
            echo "<script>alert('Gagal Mendaftar! Coba lagi.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Member - Kyyn Book Luxury</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap"
        rel="stylesheet">

    <style>
        /* --- 1. SETUP TEMA LUXURY --- */
        :root {
            --bg-dark: #121820;
            /* Latar belakang sangat gelap */
            --bg-glass: rgba(255, 255, 255, 0.05);
            --text-gold: #e2b96e;
            /* Emas Mewah */
            --text-white: #f1f1f1;
            --text-grey: #a0a0a0;
            --btn-gold: #d4af37;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-white);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            /* Aksen background halus */
            background-image: radial-gradient(circle at 50% 0%, rgba(226, 185, 110, 0.15) 0%, transparent 50%);
        }

        /* --- 2. KOTAK REGISTER (GLASS) --- */
        .register-container {
            width: 100%;
            max-width: 450px;
            padding: 40px;
            background: var(--bg-glass);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* --- 3. TYPOGRAPHY --- */
        h2 {
            font-family: 'Playfair Display', serif;
            color: var(--text-gold);
            text-align: center;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        p.subtitle {
            text-align: center;
            color: var(--text-grey);
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        /* --- 4. FORM INPUT --- */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-white);
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px 15px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            color: white;
            font-size: 0.95rem;
            outline: none;
            transition: 0.3s;
        }

        input:focus,
        textarea:focus {
            border-color: var(--text-gold);
            box-shadow: 0 0 10px rgba(226, 185, 110, 0.2);
            background: rgba(0, 0, 0, 0.5);
        }

        textarea {
            resize: none;
            height: 80px;
        }

        /* --- 5. TOMBOL EMAS --- */
        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--btn-gold), #b8860b);
            color: #121820;
            border: none;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.5);
            background: linear-gradient(135deg, #f1c40f, #d4af37);
        }

        /* --- 6. LINK LOGIN --- */
        .login-link {
            text-align: center;
            margin-top: 25px;
            font-size: 0.9rem;
            color: var(--text-grey);
        }

        .login-link a {
            color: var(--text-gold);
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .login-link a:hover {
            color: #fff;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="register-container">
        <h2>Join Membership</h2>
        <p class="subtitle">Bergabunglah untuk mengakses koleksi eksklusif.</p>

        <form method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" required placeholder="Contoh: Rizky Maulana">
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Buat username unik">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Rahasiakan sandi Anda">
            </div>

            <div class="form-group">
                <label>No. Telepon (WhatsApp)</label>
                <input type="number" name="no_telp" required placeholder="08xxxxxxxxxx">
            </div>

            <div class="form-group">
                <label>Alamat Domisili</label>
                <textarea name="alamat" required placeholder="Masukkan alamat lengkap..."></textarea>
            </div>

            <button type="submit" name="register">DAFTAR SEKARANG</button>

            <div class="login-link">
                Sudah punya akun? <a href="index.php">Login di sini</a>
            </div>
        </form>
    </div>

</body>

</html>
