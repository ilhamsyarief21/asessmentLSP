<style>
    /* Center the form vertically and horizontally */
    .container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        width: 100vw;
        overflow: hidden;
    }

    .col-md-10 {
        background-color: #f8f9fa; /* Optional: add a background color for better visibility */
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 1100px; /* Optional: Set a max-width for the form */
		margin-bottom: 80px;
    }

    /* Adjustments for smaller screens */
    @media (max-width: 768px) {
        .col-md-8 {
            max-width: 100%;
        }
    }
</style>


<?php
$update = ((isset($_GET['action']) AND $_GET['action'] == 'update') OR isset($_SESSION["pelanggan"])) ? true : false;
if ($update) {
    $sql = $connection->query("SELECT * FROM pelanggan WHERE id_pelanggan='$_SESSION[pelanggan][id]'");
    $row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no_ktp = $_POST["no_ktp"];
    $nama = $_POST["nama"];
    $email = $_POST["email"];
    $no_telp = $_POST["no_telp"];
    $alamat = $_POST["alamat"];
    $username = $_POST["username"];
    $password = md5($_POST["password"]);

    if ($update) {
        $sql = "UPDATE pelanggan SET no_ktp=?, nama=?, email=?, no_telp=?, alamat=?, username=?";
        if ($_POST["password"] != "") {
            $sql .= ", password=?";
        }
        $sql .= " WHERE id_pelanggan=?";
    } else {
        $sql = "INSERT INTO pelanggan (no_ktp, nama, email, no_telp, alamat, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    }

    $stmt = $connection->prepare($sql);

    if ($update) {
        if ($_POST["password"] != "") {
            $stmt->bind_param("ssssssi", $no_ktp, $nama, $email, $no_telp, $alamat, $username, $password, $_SESSION["pelanggan"]["id"]);
        } else {
            $stmt->bind_param("sssssi", $no_ktp, $nama, $email, $no_telp, $alamat, $username, $_SESSION["pelanggan"]["id"]);
        }
    } else {
        $stmt->bind_param("sssssss", $no_ktp, $nama, $email, $no_telp, $alamat, $username, $password);
    }

    if ($stmt->execute()) {
        echo alert("Berhasil! Silahkan login", "login.php");
    } else {
        echo alert("Gagal!", "?page=pelanggan");
    }

    $stmt->close();
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
    $connection->query("DELETE FROM pelanggan WHERE id_pelanggan='$_SESSION[pelanggan][id]'");
    echo alert("Berhasil!", "?page=pelanggan");
}
?>
<!-- Form HTML -->
<?php
$update = ((isset($_GET['action']) AND $_GET['action'] == 'update') OR isset($_SESSION["pelanggan"])) ? true : false;
if ($update) {
    $sql = $connection->query("SELECT * FROM pelanggan WHERE id_pelanggan='$_SESSION[pelanggan][id]'");
    $row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no_ktp = $_POST["no_ktp"];
    $nama = $_POST["nama"];
    $email = $_POST["email"];
    $no_telp = $_POST["no_telp"];
    $alamat = $_POST["alamat"];
    $username = $_POST["username"];
    $password = md5($_POST["password"]);

    if ($update) {
        $sql = "UPDATE pelanggan SET no_ktp=?, nama=?, email=?, no_telp=?, alamat=?, username=?";
        if ($_POST["password"] != "") {
            $sql .= ", password=?";
        }
        $sql .= " WHERE id_pelanggan=?";
    } else {
        $sql = "INSERT INTO pelanggan (no_ktp, nama, email, no_telp, alamat, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    }

    $stmt = $connection->prepare($sql);

    if ($update) {
        if ($_POST["password"] != "") {
            $stmt->bind_param("ssssssi", $no_ktp, $nama, $email, $no_telp, $alamat, $username, $password, $_SESSION["pelanggan"]["id"]);
        } else {
            $stmt->bind_param("sssssi", $no_ktp, $nama, $email, $no_telp, $alamat, $username, $_SESSION["pelanggan"]["id"]);
        }
    } else {
        $stmt->bind_param("sssssss", $no_ktp, $nama, $email, $no_telp, $alamat, $username, $password);
    }

    if ($stmt->execute()) {
        echo alert("Berhasil! Silahkan login", "login.php");
    } else {
        echo alert("Gagal!", "?page=pelanggan");
    }

    $stmt->close();
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
    $connection->query("DELETE FROM pelanggan WHERE id_pelanggan='$_SESSION[pelanggan][id]'");
    echo alert("Berhasil!", "?page=pelanggan");
}
?>
<!-- Form HTML -->

<div class="container">
		<div class="col-md-2"></div>
		<div class="col-md-10">
			<div class="page-header">
				<?php if ($update): ?>
					<h2>Update <small>data pelanggan!</small></h2>
				<?php else: ?>
					<h2>Daftar <small>sebagai pelanggan!</small></h2>
				<?php endif; ?>
			</div>
			<form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
				<div class="form-group">
					<label for="nama">Nama</label>
					<input type="text" name="nama" class="form-control" autofocus="on" <?= (!$update) ?: 'value="'.$row["nama"].'"' ?>>
				</div>
				<div class="form-group">
					<label for="no_ktp">No KTP</label>
					<input type="text" name="no_ktp" class="form-control" <?= (!$update) ?: 'value="'.$row["no_ktp"].'"' ?>>
				</div>
				<div class="form-group">
					<label for="no_telp">No Telp</label>
					<input type="text" name="no_telp" class="form-control" <?= (!$update) ?: 'value="'.$row["no_telp"].'"' ?>>
				</div>
				<div class="form-group">
					<label for="alamat">Alamat</label>
					<textarea rows="2" name="alamat" class="form-control"><?= (!$update) ? "" : $row["alamat"] ?></textarea>
				</div>
				<div class="form-group">
					<label for="email">email</label>
					<input type="email" name="email" class="form-control" <?= (!$update) ?: 'value="'.$row["email"].'"' ?>>
				</div>
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" name="username" class="form-control" <?= (!$update) ?: 'value="'.$row["username"].'"' ?>>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" class="form-control">
				</div>
				<?php if ($update): ?>
					<div class="row">
							<div class="col-md-10">
								<button type="submit" class="btn btn-warning btn-block">Update</button>
							</div>
							<div class="col-md-2">
								<a href="?page=kriteria" class="btn btn-default btn-block">Batal</a>
							</div>
					</div>
				<?php else: ?>
					<button type="submit" class="btn btn-primary btn-block">Register</button>
				<?php endif; ?>
		</form>
		</div>
		<div class="col-md-2"></div>
</div>
