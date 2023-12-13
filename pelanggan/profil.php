<?php
if (!isset($_SESSION["pelanggan"])) {
    header('location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_profil'])) {
    $id = $_SESSION["pelanggan"]["id"];
    $foto_profil_path = uploadFotoProfil($id);

    if ($foto_profil_path !== false) {
        // Update the foto_profil column in the pelanggan table
        $connection->query("UPDATE pelanggan SET foto_profil = '$foto_profil_path' WHERE id_pelanggan = $id");
    }
}

function uploadFotoProfil($id) {
    $target_dir = "assets/img/"; // Lokasi penyimpanan foto profil
    $target_file = $target_dir . basename($_FILES["foto_profil"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a real image or fake image
    $check = getimagesize($_FILES["foto_profil"]["tmp_name"]);
    if ($check === false) {
        echo "File bukan gambar.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Maaf, file sudah ada.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["foto_profil"]["size"] > 500000) {
        echo "Maaf, ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $allowed_formats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_formats)) {
        echo "Maaf, hanya file JPG, JPEG, PNG, & GIF yang diizinkan.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Maaf, file tidak dapat diunggah.";
        return false;
    } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_file)) {
            return $target_file;
        } else {
            echo "Maaf, terjadi kesalahan saat mengunggah file.";
            return false;
        }
    }
}
?>

<style>
    .btn-primary {
    background-color: #007bff;
    color: #fff;
    margin-top: 10px; /* Adjust the top margin as needed */
    margin-bottom: 10px; /* Adjust the bottom margin as needed */
    margin-right: 10px; /* Adjust the right margin as needed */
    margin-left: 0px; /* Adjust the left margin as needed */
}

</style>

<div class="row">
    <div class="col-md-4 hidden-print">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="text-center">Profil</h3>
            </div>
            <div class="panel-body">
                <?php if (isset($_SESSION["pelanggan"])): ?>
                    <?php $id = $_SESSION["pelanggan"]["id"]; ?>
                    <?php if ($query = $connection->query("SELECT * FROM pelanggan WHERE id_pelanggan=$id")): ?>
                        <?php while ($data = $query->fetch_assoc()): ?>
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group text-center">
                                    <label for="foto_profil">Foto Profil</label><br>
                                    <?php if (!empty($data['foto_profil'])): ?>
                                        <img src="<?= $data['foto_profil'] ?>" alt="Foto Profil" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                    <?php endif; ?>
                                    <input type="file" name="foto_profil">
                                </div>

                                <div class="form-group">
                                    <label for="nama">Nama Lengkap</label>
                                    <input disabled="on" type="text" name="nama" class="form-control" value="<?= $data['nama'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input disabled="on" type="email" name="email" class="form-control" value="<?= $data['email'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="no_telp">Telpon</label>
                                    <input disabled="on" type="text" name="no_telp" class="form-control" value="<?= $data['no_telp'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input disabled="on" type="text" name="username" class="form-control" value="<?= $data['username'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <input disabled="on" type="text" name="alamat" class="form-control" value="<?= $data['alamat'] ?>">
                                    <button type="submit" class="btn btn-primary">Simpan Foto Profil</button>
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
    <!-- denda -->
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
                                <?php
                                    $dueDate = strtotime($data['tgl_ambil']);
                                    $returnDate = strtotime($data['tgl_kembali']);
                                    $daysLate = max(0, floor(($returnDate - $dueDate) / (60 * 60 * 24))); 
                                    $penaltyPerDay = 40000; 
                                    $penaltyAmount = $daysLate > 5 ? $penaltyPerDay * $daysLate : 0; 
                                    $totalDenda = number_format($penaltyAmount, 2); 
                                ?>

                                <tr>
                                    <td><?=$no++?></td>
                                    <td><?=$data['jaminan']?></td>
                                    <td><?=date("d-m-Y H:i:s", strtotime($data['tgl_ambil']))?></td>
                                    <td><?=date("d-m-Y H:i:s", strtotime($data['tgl_kembali']))?></td>
                                    <td>Rp.<?=number_format($data['total_harga'])?>,-</td>
                                    <td>Rp.<?=$totalDenda?>,-</td>
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
 