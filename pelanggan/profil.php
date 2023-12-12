<?php
if (!isset($_SESSION["pelanggan"])) {
  header('location: login.php');
}
?>
<div class="row">
  <div class="col-md-4 hidden-print">
    <div class="panel panel-info">
        <div class="panel-heading"><h3 class="text-center">Profil</h3></div>
        <div class="panel-body">
            <?php if (isset($_SESSION["pelanggan"])): ?>
                <?php $id = $_SESSION["pelanggan"]["id"]; if ($query = $connection->query("SELECT * FROM pelanggan WHERE id_pelanggan=$id")): ?>
                    <?php while ($data = $query->fetch_assoc()): ?>
                        <form>
                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input disabled="on" type="text" name="nama" class="form-control" value="<?=$data['nama']?>">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input disabled="on" type="email" name="email" class="form-control" value="<?=$data['email']?>">
                            </div>
                            <div class="form-group">
                                <label for="no_telp">Telpon</label>
                                <input disabled="on" type="text" name="no_telp" class="form-control" value="<?=$data['no_telp']?>">
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input disabled="on" type="text" name="username" class="form-control" value="<?=$data['username']?>">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input disabled="on" type="text" name="alamat" class="form-control" value="<?=$data['alamat']?>">
                            </div>
                        </form>
                    <?php endwhile ?>
                <?php endif ?>
            <?php endif ?>
        </div>
    </div>
  </div>

  <div class="col-md-8">
    <div class="row">
        <div class="panel panel-info">
          <div class="panel-heading"><h3 class="text-center">Riwayat Transaksi</h3></div>
          <div class="panel-body">
            <?php if ($query = $connection->query("SELECT * FROM transaksi WHERE id_pelanggan=$id")): ?>
                <?php $no = 1; ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Total</th>
                            <th>Lama</th>
                            <th>Jaminan</th>
                            <th>Tanggal</th>
                            <th>Jatuh Tempo</th>
                            <th class="hidden-print"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($data = $query->fetch_assoc()): ?>
                            <tr>
                                <td><?=$no++?></td>
                                <td>Rp.<?=number_format($data['total_harga'])?>,-</td>
                                <td><?=$data['lama']?> Hari</td>
                                <td><?=$data['jaminan']?></td>
                                <td><?=date("d-m-Y H:i:s", strtotime($data['tgl_sewa']))?></td>
                                <td><?=date("d-m-Y H:i:s", strtotime($data['jatuh_tempo']))?></td>
                                <td class="hidden-print">
                                  <div class="btn-group">
                                      <?php if (!$data['konfirmasi'] AND !$data["pembatalan"]): ?>
                                          <a href="?page=konfirmasi&id=<?= $data['id_transaksi'] ?>" class="btn btn-success btn-xs">Konfirmasi</a>
                                      <?php endif ?>
                                      <a href="?page=detail&id=<?= $data['id_transaksi'] ?>" class="btn btn-info btn-xs">Detail</a>
                                  </div>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php endif ?>
          </div>
          <div class="panel-footer hidden-print ">
              <a onClick="window.print();return false" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i></a>
          </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-info">
          <div class="panel-heading"><h3 class="text-center">Riwayat Denda</h3></div>
          <div class="panel-body">
            <?php if ($query = $connection->query("SELECT * FROM transaksi WHERE id_pelanggan=$id AND denda <> ''")): ?>
                <?php $no = 1; ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jaminan</th>
                            <th>Tanggal Ambil</th>
                            <th>Tanggal Kembali</th>
                            <th>Total Harga</th>
                            <th>Total Denda</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($data = $query->fetch_assoc()): ?>
                            <tr>
                                <td><?=$no++?></td>
                                <td><?=$data['jaminan']?></td>
                                <td><?=date("d-m-Y H:i:s", strtotime($data['tgl_ambil']))?></td>
                                <td><?=date("d-m-Y H:i:s", strtotime($data['tgl_kembali']))?></td>
                                <td>Rp.<?=number_format($data['total_harga'])?>,-</td>
                                <td>Rp.<?=number_format($data['denda'])?>,-</td>
                                <td>
                                  <a href="?page=detail&id=<?= $data['id_transaksi'] ?>" class="btn btn-warning btn-xs">Lihat Transaksi</a>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php endif ?>
          </div>
          <div class="panel-footer hidden-print ">
              <a onClick="window.print();return false" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i></a>
          </div>
        </div>
    </div>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Handle form submission for pengembalian
            if (isset($_POST["kembalikan"])) {
                $kode = $_POST["kode"];
                $tgl_kembali = date("Y-m-d H:i:s"); // Ambil tanggal dan waktu hari ini

                // Update tanggal pengembalian dalam tabel transaksi
                $queryUpdate = "UPDATE transaksi SET tgl_kembali = '$tgl_kembali' WHERE kode = '$kode' AND tgl_kembali IS NULL";
                $connection->query($queryUpdate);

                // Tambahkan log atau proses lain yang diperlukan setelah pengembalian

                echo "Mobil telah dikembalikan pada " . date("Y-m-d H:i:s") . ". Terima kasih!";
            }
        }
        ?>


        <div class="row">
            <div class="panel panel-info">
                <div class="panel-heading"><h3 class="text-center">Pengembalian Kamera</h3></div>
                <div class="panel-body">
                    <!-- Formulir pengembalian -->
                    <form method="post" action="" id="kembalikanForm">
                        <div class="form-group">
                            <label for="kode">Masukkan Kode Transaksi</label>
                            <input type="text" name="kode" class="form-control" pattern="[0-9]+" title="Hanya angka yang diperbolehkan" required>
                        </div>
                        <button type="submit" name="kembalikan" class="btn btn-success">Kembalikan Mobil</button>
                    </form>
                </div>
                <div class="panel-footer hidden-print">
                    <a onClick="window.print();return false" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i></a>
                </div>
            </div>
        </div>

<script>
document.getElementById('kembalikanForm').addEventListener('submit', function() {
    // Tambahkan input tersembunyi untuk menyimpan waktu detik saat tombol diklik
    var detikField = document.createElement('input');
    detikField.setAttribute('type', 'hidden');
    detikField.setAttribute('name', 'detik');
    detikField.setAttribute('value', Math.floor(Date.now() / 1000)); // Waktu detik saat ini
    this.appendChild(detikField);
});
</script>