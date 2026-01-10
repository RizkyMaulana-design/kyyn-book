<?php
include 'koneksi.php';
$id = $_GET['id'];
$ambil = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku='$id'");
$data = mysqli_fetch_assoc($ambil);

if (isset($_POST['update'])) {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun'];
    $stok = $_POST['stok'];

    mysqli_query($koneksi, "UPDATE buku SET 
        judul_buku='$judul', pengarang='$pengarang', penerbit='$penerbit', 
        tahun_terbit='$tahun', stok='$stok' WHERE id_buku='$id'");

    echo "<script>alert('Data Buku Berhasil Diupdate!'); window.location='kelola_buku.php';</script>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Buku</title>
</head>

<body>
    <h3>Edit Data Buku</h3>
    <form method="POST">
        <label>Judul Buku:</label><br>
        <input type="text" name="judul" value="<?php echo $data['judul_buku']; ?>"><br><br>

        <label>Pengarang:</label><br>
        <input type="text" name="pengarang" value="<?php echo $data['pengarang']; ?>"><br><br>

        <label>Penerbit:</label><br>
        <input type="text" name="penerbit" value="<?php echo $data['penerbit']; ?>"><br><br>

        <label>Tahun Terbit:</label><br>
        <input type="number" name="tahun" value="<?php echo $data['tahun_terbit']; ?>"><br><br>

        <label>Jumlah Stok:</label><br>
        <input type="number" name="stok" value="<?php echo $data['stok']; ?>"><br><br>

        <button type="submit" name="update">Simpan Perubahan</button>
        <a href="kelola_buku.php">Batal</a>
    </form>
</body>

</html>