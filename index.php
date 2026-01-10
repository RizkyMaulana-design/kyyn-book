<?php
session_start();
include 'koneksi.php';

// Jika sudah login, langsung lempar ke dashboard
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("location:kelola_buku.php");
    } else {
        header("location:user_dashboard.php");
    }
    exit;
}

// LOGIKA LOGIN
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['nama'] = $data['nama_lengkap'];

        $tujuan = ($data['role'] == 'admin') ? 'kelola_buku.php' : 'user_dashboard.php';

        // --- SPLASH SCREEN START ---
        ?>
        <!DOCTYPE html>
        <html lang="id">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Access Granted</title>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;800&display=swap" rel="stylesheet">
            <style>
                body {
                    margin: 0;
                    padding: 0;
                    background: #000;
                    overflow: hidden;
                    font-family: 'Poppins', sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    color: white;
                }

                #warpCanvas {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 1;
                }

                .loader-content {
                    position: relative;
                    z-index: 10;
                    text-align: center;
                }

                h1 {
                    font-size: 3rem;
                    margin: 0;
                    letter-spacing: 2px;
                    text-transform: uppercase;
                    animation: pulse 1s infinite alternate;
                }

                h1 span {
                    color: #00d2ff;
                    text-shadow: 0 0 20px #00d2ff;
                }

                p {
                    color: #aaa;
                    margin-top: 10px;
                    font-size: 1.2rem;
                    letter-spacing: 3px;
                }

                .progress-container {
                    width: 300px;
                    height: 6px;
                    background: rgba(255, 255, 255, 0.1);
                    margin: 30px auto 0;
                    border-radius: 10px;
                    overflow: hidden;
                }

                .progress-bar {
                    width: 0%;
                    height: 100%;
                    background: #00d2ff;
                    box-shadow: 0 0 15px #00d2ff;
                    animation: load 2.5s ease-in-out forwards;
                }

                @keyframes pulse {
                    from {
                        opacity: 0.8;
                        transform: scale(1);
                    }

                    to {
                        opacity: 1;
                        transform: scale(1.05);
                    }
                }

                @keyframes load {
                    0% {
                        width: 0%;
                    }

                    100% {
                        width: 100%;
                    }
                }
            </style>
        </head>

        <body>
            <canvas id="warpCanvas"></canvas>
            <div class="loader-content">
                <h1>ACCESS <span>GRANTED</span></h1>
                <p>MEMASUKI SISTEM...</p>
                <div class="progress-container">
                    <div class="progress-bar"></div>
                </div>
            </div>
            <script>
                const canvas = document.getElementById('warpCanvas');
                const ctx = canvas.getContext('2d');
                let width, height, stars = []; const numStars = 500; const speed = 20;
                function resize() { width = window.innerWidth; height = window.innerHeight; canvas.width = width; canvas.height = height; }
                window.addEventListener('resize', resize); resize();
                function createStar() { return { x: Math.random() * width - width / 2, y: Math.random() * height - height / 2, z: Math.random() * width }; }
                for (let i = 0; i < numStars; i++) stars.push(createStar());
                function animate() {
                    ctx.fillStyle = "rgba(0, 0, 0, 0.4)"; ctx.fillRect(0, 0, width, height); ctx.fillStyle = "white";
                    for (let i = 0; i < numStars; i++) {
                        let s = stars[i]; s.z -= speed;
                        if (s.z <= 0) { stars[i] = createStar(); stars[i].z = width; s = stars[i]; }
                        const x = (s.x / s.z) * (width / 2) + (width / 2); const y = (s.y / s.z) * (height / 2) + (height / 2);
                        const size = (1 - s.z / width) * 3;
                        if (x >= 0 && x < width && y >= 0 && y < height) { ctx.beginPath(); ctx.arc(x, y, size, 0, Math.PI * 2); ctx.fill(); }
                    } requestAnimationFrame(animate);
                } animate();
                setTimeout(function () { window.location.href = '<?php echo $tujuan; ?>'; }, 2500);
            </script>
        </body>

        </html>
        <?php
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal - Kyyn Book</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        /* SAMA SEPERTI SEBELUMNYA */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #000;
            overflow: hidden;
            height: 100vh;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        #starfield {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .login-box {
            position: relative;
            z-index: 10;
            background: rgba(20, 20, 30, 0.6);
            width: 500px;
            padding: 60px 50px;
            border-radius: 30px;
            border: 1px solid rgba(0, 210, 255, 0.3);
            backdrop-filter: blur(10px);
            box-shadow: 0 0 50px rgba(0, 210, 255, 0.1);
            text-align: center;
            animation: zoomIn 1s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.8) translateY(50px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .typing-container {
            height: 30px;
            margin-bottom: 15px;
        }

        .typing-text {
            font-size: 0.9rem;
            color: #00d2ff;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-weight: 600;
            text-shadow: 0 0 10px rgba(0, 210, 255, 0.8);
        }

        .cursor {
            display: inline-block;
            width: 2px;
            height: 15px;
            background-color: #00d2ff;
            animation: blink 0.8s infinite;
            vertical-align: middle;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .login-box h1 {
            color: white;
            font-weight: 800;
            font-size: 3.5rem;
            margin-bottom: 5px;
            letter-spacing: 2px;
            background: -webkit-linear-gradient(#fff, #aaa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-box h1 span {
            background: -webkit-linear-gradient(#00d2ff, #3a7bd5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .input-group {
            margin-bottom: 25px;
            text-align: left;
        }

        label {
            color: #aaa;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: block;
            letter-spacing: 1px;
        }

        input {
            width: 100%;
            padding: 18px 25px;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            color: white;
            font-size: 1.1rem;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            background: rgba(0, 0, 0, 0.8);
            border-color: #00d2ff;
            box-shadow: 0 0 25px rgba(0, 210, 255, 0.4);
            transform: scale(1.02);
        }

        button {
            width: 100%;
            padding: 18px;
            background: linear-gradient(90deg, #00d2ff, #3a7bd5);
            border: none;
            border-radius: 50px;
            color: white;
            font-weight: 800;
            font-size: 1.2rem;
            letter-spacing: 2px;
            cursor: pointer;
            margin-top: 20px;
            transition: 0.3s;
            box-shadow: 0 0 20px rgba(0, 210, 255, 0.5);
            position: relative;
            overflow: hidden;
        }

        button:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 0 40px rgba(0, 210, 255, 0.8);
        }

        .link-register {
            display: block;
            margin-top: 25px;
            color: #666;
            font-size: 0.9rem;
            text-decoration: none;
            transition: 0.3s;
        }

        .link-register:hover {
            color: #fff;
        }

        .alert-error {
            background: rgba(255, 50, 50, 0.1);
            color: #ff5555;
            padding: 15px;
            border-radius: 15px;
            border: 1px solid #ff5555;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <canvas id="starfield"></canvas>
    <div class="login-box">
        <div class="typing-container"><span class="typing-text" id="typewriter"></span><span class="cursor"></span>
        </div>
        <h1>Kyyn<span>Book</span></h1>

        <?php if (isset($error)) {
            echo "<div class='alert-error'>$error</div>";
        } ?>

        <form method="POST" autocomplete="off">
            <div class="input-group">
                <label>USERNAME</label>
                <input type="text" name="username" placeholder="Identitas Pengguna" required autocomplete="off">
            </div>

            <div class="input-group">
                <label>KATA SANDI</label>
                <input type="password" name="password" placeholder="Kode Keamanan" required autocomplete="new-password">
            </div>

            <button type="submit" name="login">AKSES MASUK</button>
        </form>

        <a href="register.php" class="link-register">Daftar Akun Baru</a>
    </div>

    <script>
        const canvas = document.getElementById('starfield');
        const ctx = canvas.getContext('2d');
        let width, height; let stars = []; const numStars = 800; const speed = 4;
        function resize() { width = window.innerWidth; height = window.innerHeight; canvas.width = width; canvas.height = height; }
        window.addEventListener('resize', resize); resize();
        function createStar() { return { x: Math.random() * width - width / 2, y: Math.random() * height - height / 2, z: Math.random() * width }; }
        for (let i = 0; i < numStars; i++) stars.push(createStar());
        function animateStars() {
            ctx.fillStyle = "rgba(0, 0, 0, 0.8)"; ctx.fillRect(0, 0, width, height); ctx.fillStyle = "#ffffff";
            for (let i = 0; i < numStars; i++) {
                let s = stars[i]; s.z -= speed; if (s.z <= 0) { stars[i] = createStar(); stars[i].z = width; s = stars[i]; }
                const x = (s.x / s.z) * (width / 2) + (width / 2); const y = (s.y / s.z) * (height / 2) + (height / 2);
                const size = (1 - s.z / width) * 3;
                if (x >= 0 && x < width && y >= 0 && y < height) { ctx.beginPath(); ctx.arc(x, y, size, 0, Math.PI * 2); ctx.fill(); }
            } requestAnimationFrame(animateStars);
        } animateStars();

        // Teks Mengetik
        const textElement = document.getElementById('typewriter');
        const phrases = ["SYSTEM READY...", "INITIALIZING...", "WELCOME USER..."];
        let phraseIndex = 0; let charIndex = 0; let isDeleting = false;
        function type() {
            const currentPhrase = phrases[phraseIndex];
            if (isDeleting) { textElement.textContent = currentPhrase.substring(0, charIndex - 1); charIndex--; }
            else { textElement.textContent = currentPhrase.substring(0, charIndex + 1); charIndex++; }
            if (!isDeleting && charIndex === currentPhrase.length) { isDeleting = true; setTimeout(type, 2000); return; }
            if (isDeleting && charIndex === 0) { isDeleting = false; phraseIndex = (phraseIndex + 1) % phrases.length; }
            setTimeout(type, isDeleting ? 50 : 100);
        } type();
    </script>
</body>

</html>