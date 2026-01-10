<?php
session_start();
session_destroy(); // Menghapus semua data sesi (login)
header("location:index.php"); // Kembalikan ke halaman login
?>