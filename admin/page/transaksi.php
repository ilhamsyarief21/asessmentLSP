<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Transaksi</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">

  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th, td {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }

    th {
      background-color: #f2f2f2;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    /* Gaya untuk memusatkan gambar di tengah sel tabel */
    td img {
      display: block;
      margin: 0 auto;
    }
  </style>
</head>
<body>

<?php
$query = $connection->query("SELECT t.id_transaksi, m.nama_kamera, t.lama, t.jaminan, t.total_harga, t.tgl_sewa, t.tgl_ambil, t.tgl_kembali, t.jatuh_tempo, t.status, t.konfirmasi, t.pembatalan, t.statuspembayaran, k.bukti FROM transaksi t JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan JOIN kameraku m ON t.id_kameraku = m.id_kameraku LEFT JOIN konfirmasi k ON t.id_transaksi = k.id_transaksi");
?>

<table>
  <thead>
    <tr>
      <th>ID Transaksi</th>
      <th>Nama Kamera</th>
      <th>Lama</th>
      <th>Jaminan</th>
      <th>Total Harga</th>
      <th>Tanggal Sewa</th>
      <th>Tanggal Ambil</th>
      <th>Tanggal Kembali</th>
      <th>Jatuh Tempo</th>
      <th>Konfirmasi</th>
      <th>Foto Bukti Pembayaran</th>
      <th>Pembatalan</th>
      <th>Status Pembayaran</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($r = $query->fetch_assoc()): ?>
      <tr>
        <td><?= $r['id_transaksi'] ?></td>
        <td><?= $r['nama_kamera'] ?></td>
        <td><?= $r['lama'] ?></td>
        <td><?= $r['jaminan'] ?></td>
        <td><?= $r['total_harga'] ?></td>
        <td><?= $r['tgl_sewa'] ?></td>
        <td><?= $r['tgl_ambil'] ?></td>
        <td><?= $r['tgl_kembali'] ?></td>
        <td><?= $r['jatuh_tempo'] ?></td>
        <td><?= ($r['konfirmasi'] == 1) ? 'Sudah' : 'Belum' ?></td>
        <td>
          <?php if ($r['konfirmasi'] == 1 && $r['bukti']): ?>
            <a href="/kamerakuLSP/assets/img/bukti/<?= $r['bukti'] ?>" data-lightbox="bukti" data-title="Bukti Pembayaran">
              <img src="/kamerakuLSP/assets/img/bukti/<?= $r['bukti'] ?>" alt="Bukti Pembayaran" style="max-width: 100px; border-radius: 5px;" class="image-link">
            </a>
          <?php else: ?>
            Belum melakukan pembayaran
          <?php endif; ?>
        </td>


        <td><?= ($r['pembatalan'] == 1) ? 'Sudah' : 'Tidak' ?></td>
        <td>
          <span class="label label-<?=($r['statuspembayaran'] == 'Lunas') ? "success" : "danger"?>">
            <?=($r['statuspembayaran'] == 'Lunas') ? "Lunas" : "Belum Lunas"?>
          </span>
        </td>
        <td>
          <form method="POST" action="proses_edit_statuspembayaran.php" style="margin-top: 10px;">
            <input type="hidden" name="id_transaksi" value="<?= $r['id_transaksi'] ?>">
            <select name="statuspembayaran" style="padding: 8px; font-size: 14px;">
              <option value="Lunas" <?= ($r['statuspembayaran'] == 'Lunas') ? 'selected' : '' ?>>Lunas</option>
              <option value="Belum Lunas" <?= ($r['statuspembayaran'] == 'Belum Lunas') ? 'selected' : '' ?>>Belum Lunas</option>
            </select>
            <button type="submit" style="background-color: orange; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">Ubah</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

</body>
</html>