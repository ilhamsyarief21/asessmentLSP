<head>
    <!-- Sertakan jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Sertakan jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<form class="form-inline hidden-print" action="<?=$_SERVER["REQUEST_URI"]?>" method="post">
    <label>Mobil :</label>
    <select class="form-control" name="id_mobil">
        <option>---</option>
        <?php $query = $connection->query("SELECT * FROM mobil"); while ($r = $query->fetch_assoc()): ?>
            <option value="<?=$r["id_mobil"]?>"><?=$r["nama_mobil"]?> | <?=$r["no_mobil"]?></option>
        <?php endwhile; ?>
    </select>
    <label>Tgl :</label>
    <input type="text" class="form-control datepicker" name="start">
    <label>s/d</label>
    <input type="text" class="form-control datepicker" name="stop">
    <button type="submit" class="btn btn-primary">Tampilkan</button>
</form>
	<br>
	<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_mobil = isset($_POST['id_mobil']) ? intval($_POST['id_mobil']) : 0;
    $start = isset($_POST['start']) ? $_POST['start'] : '';
    $stop = isset($_POST['stop']) ? $_POST['stop'] : '';

    if ($id_mobil > 0 && !empty($start) && !empty($stop)) {
        $query = "SELECT d.no_mobil, d.nama_mobil, d.merk FROM transaksi a
                  JOIN pelanggan b USING(id_pelanggan)
                  JOIN mobil d USING(id_mobil)
                  WHERE d.id_mobil=$id_mobil AND a.tgl_sewa BETWEEN '$start' AND '$stop'";

        $result = $connection->query($query);

        if ($result) {
?>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="text-center">LAPORAN PENYEWAAN PERKAMERA</h3>
                    <br>
                    <h4 class="text-center">tgl: <?= $_POST["start"] . " s/d " . $_POST["stop"] ?></h4>
                </div>
                <div class="panel-body">
                    <?php
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <form class="form-inline">
                            <table>
                                <tr>
                                    <td>
                                        <label>Nomor Seri Kamera</label>
                                    </td>
                                    <td>&nbsp;:&nbsp;
                                        <input type="text" value="<?= $row['no_mobil'] ?>" class="form-control" disabled="on"><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Nama Kamera</label>
                                    </td>
                                    <td>&nbsp;:&nbsp;
                                        <input type="text" value="<?= $row['nama_mobil'] ?>" class="form-control" disabled="on"><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Merk</label>
                                    </td>
                                    <td>&nbsp;:&nbsp;
                                        <input type="text" value="<?= $row['merk'] ?>" class="form-control" disabled="on">
                                    </td>
                                </tr>
                            </table>
                        </form>
                    <?php
                    }
                    ?>
                    <br>
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pelanggan</th>
                                <th>Harga Sewa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = "SELECT b.nama, d.harga FROM transaksi a
                                      JOIN pelanggan b USING(id_pelanggan)
                                      JOIN mobil d USING(id_mobil)
                                      WHERE d.id_mobil=$id_mobil AND a.tgl_sewa BETWEEN '$start' AND '$stop'";
                            $result = $connection->query($query);
                            if ($result) {
                                while ($r = $result->fetch_assoc()) {
                            ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $r['nama'] ?></td>
                                        <td>Rp.<?= number_format($r['harga']) ?></td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer hidden-print">
                    <a onClick="window.print();return false" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i></a>
                </div>
            </div>
<?php
        } else {
            echo "Tidak ada hasil laporan.";
        }
    } else {
        echo "Harap pilih mobil dan isi tanggal dengan benar.";
    }
}
?>


<script>
    $(document).ready(function () {
        // Aktifkan datepicker pada elemen input dengan class "form-control"
        $(".form-control").datepicker({
            dateFormat: 'yy-mm-dd', // Format tanggal yang diinginkan
            changeMonth: true,
            changeYear: true
        });
    });
</script>
