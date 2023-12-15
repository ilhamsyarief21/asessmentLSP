<?php
$update = (isset($_GET['action']) && $_GET['action'] == 'update') ? true : false;
if ($update) {
    $id_pelanggan = $connection->real_escape_string($_GET['key']);
    $sql = $connection->query("SELECT * FROM pelanggan WHERE id_pelanggan='$id_pelanggan'");
    $row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no_ktp = $connection->real_escape_string($_POST['no_ktp']);
    $nama = $connection->real_escape_string($_POST['nama']);
    $email = $connection->real_escape_string($_POST['email']);
    $alamat = $connection->real_escape_string($_POST['alamat']);
    $no_telp = $connection->real_escape_string($_POST['no_telp']);
    $username = $connection->real_escape_string($_POST['username']);
    $password = md5($_POST['password']); // It's better to use password_hash() for more security

    if ($update) {
        $id_pelanggan = $connection->real_escape_string($_GET['key']);
        $sql = "UPDATE pelanggan SET no_ktp='$no_ktp', nama='$nama', email='$email', alamat='$alamat', no_telp='$no_telp', username='$username'";
        if (!empty($_POST["password"])) {
            $sql .= ", password='$password'";
        }
        $sql .= " WHERE id_pelanggan='$id_pelanggan'";
    } else {
        // Sesuaikan kolom sesuai dengan struktur tabel pelanggan
        $sql = "INSERT INTO pelanggan (no_ktp, nama, email, alamat, no_telp, username, password) VALUES ('$no_ktp', '$nama', '$email', '$alamat', '$no_telp', '$username', '$password')";
    }

    if ($connection->query($sql)) {
        echo alert("Berhasil!", "?page=pelanggan");
    } else {
        echo alert("Gagal!", "?page=pelanggan");
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id_pelanggan = $connection->real_escape_string($_GET['key']);
    $connection->query("DELETE FROM pelanggan WHERE id_pelanggan='$id_pelanggan'");
    echo alert("Berhasil!", "?page=pelanggan");
}
?>
<div class="row">
<div class="col-md-4 hidden-print">
	
	    <div class="panel panel-<?= ($update) ? "warning" : "info" ?>">
	        <div class="panel-heading"><h3 class="text-center"><?= ($update) ? "EDIT" : "TAMBAH" ?></h3></div>
	        <div class="panel-body">
	            <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
	                <div class="form-group">
	                    <label for="nama">Nama</label>
	                    <input type="text" name="nama" class="form-control" <?= (!$update) ?: 'value="'.$row["nama"].'"' ?>>
	                </div>
	                <div class="form-group">
	                    <label for="nama">No KTP</label>
	                    <input type="text" name="no_ktp" class="form-control" <?= (!$update) ?: 'value="'.$row["no_ktp"].'"' ?>>
	                </div>
	                <div class="form-group">
	                    <label for="no_telp">Telp</label>
	                    <input type="text" name="no_telp" class="form-control" <?= (!$update) ?: 'value="'.$row["no_telp"].'"' ?>>
	                </div>
	                <div class="form-group">
	                    <label for="email">Email</label>
	                    <input type="text" name="email" class="form-control" <?= (!$update) ?: 'value="'.$row["email"].'"' ?>>
	                </div>
	                <div class="form-group">
	                    <label for="alamat">Alamat</label>
	                    <input type="text" name="alamat" class="form-control" <?= (!$update) ?: 'value="'.$row["alamat"].'"' ?>>
	                </div>
	                <div class="form-group">
	                    <label for="username">Username</label>
	                    <input type="text" name="username" class="form-control" <?= (!$update) ?: 'value="'.$row["username"].'"' ?>>
	                </div>
	                <div class="form-group">
	                    <label for="password">Password</label>
	                    <input type="password" name="password" class="form-control">
			                <?php if ($update): ?>
												<span class="help-block">*) Kosongkan jika tidak diubah</span>
											<?php endif; ?>
	                </div>
	                <button type="submit" class="btn btn-<?= ($update) ? "warning" : "info" ?> btn-block">Simpan</button>
	                <?php if ($update): ?>
										<a href="?page=pelanggan" class="btn btn-info btn-block">Batal</a>
									<?php endif; ?>
	            </form>
	        </div>
	    </div>
	</div>
	<div class="col-md-8">
	    <div class="panel panel-info">
	        <div class="panel-heading"><h3 class="text-center">DAFTAR PELANGGAN</h3></div>
	        <div class="panel-body">
	            <table class="table table-condensed">
	                <thead>
	                    <tr>
	                        <th>No</th>
	                        <th>Nama</th>
	                        <th>Telp</th>
	                        <th>Email</th>
	                        <th>Username</th>
	                        <th>Alamat</th>
	                        <th></th>
	                        <th class="hidden-print"></th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <?php $no = 1; ?>
	                    <?php if ($query = $connection->query("SELECT * FROM pelanggan")): ?>
	                        <?php while($row = $query->fetch_assoc()): ?>
	                        <tr>
	                            <td><?=$no++?></td>
															<td><?=$row['nama']?></td>
															<td><?=$row['no_telp']?></td>
															<td><?=$row['email']?></td>
															<td><?=$row['username']?></td>
															<td><?=$row['alamat']?></td>
	                            <td>
	                            <td class="hidden-print">
	                                <div class="btn-group">
	                                    <a href="?page=pelanggan&action=update&key=<?=$row['id_pelanggan']?>" class="btn btn-warning btn-xs">Edit</a>
	                                    <a href="?page=pelanggan&action=delete&key=<?=$row['id_pelanggan']?>" class="btn btn-danger btn-xs">Hapus</a>
	                                </div>
	                            </td>
	                        </tr>
	                        <?php endwhile ?>
	                    <?php endif ?>
	                </tbody>
	            </table>
	        </div>
	        	<div class="panel-footer hidden-print">
			        <a onClick="window.print();return false" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i></a>
			    </div>
	    </div>
	</div>
</div>
