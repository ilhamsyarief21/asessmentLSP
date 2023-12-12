<?php
// Sertakan file koneksi atau inisialisasi koneksi di sini
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Pastikan untuk memvalidasi dan membersihkan data yang diterima dari formulir
  $id_transaksi = $_POST['id_transaksi'];
  $statuspembayaran = $_POST['statuspembayaran'];

  // Lakukan update nilai 'statuspembayaran' di database menggunakan query UPDATE
  $connection->query("UPDATE transaksi SET statuspembayaran='$statuspembayaran' WHERE id_transaksi='$id_transaksi'");

  // Redirect ke halaman transaksi di dalam folder admin
  header("Location: ../../kamerakuLSP/admin");
  exit();
}
?>
