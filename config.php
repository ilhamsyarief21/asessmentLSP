<?php
date_default_timezone_set('Asia/Jakarta');
$now = date("Y-m-d H:i:s");

if (!$connection = new Mysqli("localhost", "root", "", "kamerakuLSP")) {
  echo "<h3>ERROR: Koneksi database gagal!</h3>";
}

function page($page) {
  return "pelanggan/" . $page . ".php";
}

function adminPage($page) {
  return "page/" . $page . ".php";
}

function alert($msg, $to = null) {
  $to = ($to) ? $to : $_SERVER["PHP_SELF"];
  return "<script>alert('{$msg}');window.location='{$to}';</script>";
}

$query = $connection->query("SELECT a.id_mobil, a.id_transaksi, (DATEDIFF(NOW(), a.tgl_ambil)) AS tgl FROM transaksi a WHERE a.status='0'");
while ($data = $query->fetch_assoc()) {
    if ($data["tgl"] >= 0 && isset($data["id_mobil"]) && isset($data["id_transaksi"]) && isset($data["id_supir"])) {
        $connection->query("UPDATE mobil SET status='0' WHERE id_mobil={$data['id_mobil']}");
        $q = $connection->query("SELECT id_supir FROM detail_transaksi WHERE id_transaksi={$data['id_transaksi']}");
        if ($q->num_rows) {
            $supirData = $q->fetch_assoc();
            $idSupir = $supirData['id_supir'];
            $connection->query("UPDATE supir SET status='0' WHERE id_supir={$idSupir}");
        }
    }
}

$query = $connection->query("SELECT a.jatuh_tempo, a.id_transaksi, a.id_mobil, (TIMESTAMPDIFF(HOUR, a.tgl_sewa, NOW())) AS tempo FROM transaksi a WHERE a.konfirmasi='0'");
while ($data = $query->fetch_assoc()) {
  if ($data["tempo"] > 3) {
    $connection->query("UPDATE transaksi SET pembatalan='1' WHERE id_transaksi={$data['id_transaksi']}");
    $connection->query("UPDATE mobil SET status='1' WHERE id_mobil={$data['id_mobil']}");
    $q = $connection->query("SELECT id_supir FROM detail_transaksi WHERE id_transaksi={$data['id_transaksi']}");
    if ($q->num_rows) {
      $supirData = $q->fetch_assoc();
      $idSupir = $supirData['id_supir'];
      @$connection->query("UPDATE supir SET status='1' WHERE id_supir={$idSupir}");
      @$connection->query("DELETE FROM detail_transaksi WHERE id_transaksi={$data['id_transaksi']}");
    }
  }
}

$sql = "SELECT
          a.id_transaksi,
          (
            TIMESTAMPDIFF(
              HOUR,
              ADDDATE(a.tgl_ambil, INTERVAL a.lama DAY),
              a.tgl_kembali
            )
          ) AS terlambat,
          35000 * (TIMESTAMPDIFF(HOUR, ADDDATE(a.tgl_ambil, INTERVAL a.lama DAY), a.tgl_kembali)) AS denda
        FROM transaksi a
        WHERE a.tgl_kembali <> ''";
$query = $connection->query($sql);
while ($a = $query->fetch_assoc()) {
  if ($a["denda"] > 0) {
      if (!$connection->query("UPDATE transaksi SET denda={$a['denda']} WHERE id_transaksi={$a['id_transaksi']}")) {
        die("Hitung denda otomatis gagal.");
      }
  }
}
?>
