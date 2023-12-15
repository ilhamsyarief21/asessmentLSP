<?php
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password_submit'])) {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Cek apakah password dan konfirmasi password sesuai
    if ($new_password != $confirm_password) {
        echo "Password dan konfirmasi password tidak sesuai.";
    } else {
        // Reset password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $updateQuery = $connection->prepare("UPDATE pelanggan SET password = ?, reset_token = NULL WHERE reset_token = ?");
        $updateQuery->bind_param("ss", $hashed_password, $token);

        if ($updateQuery->execute()) {
            echo "Password berhasil direset. Silakan login dengan password baru Anda.";
        } else {
            echo "Gagal mereset password. Silakan coba lagi.";
        }
    }
} else {
    echo "Aksi tidak valid.";
}
?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset Password</title>
            <!-- Add your CSS styles here -->
            <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-image: url(assets/img/bb.jpg);
            background-size: cover;
        }

        .container {
            width: 100%;
        }

        .custom-width {
            width: 100%;
            max-width: 400px; /* Sesuaikan lebar maksimum sesuai kebutuhan */
        }

        .panel {
            width: 100%;
            margin: 0;
            background-color: #fff; /* Warna latar belakang putih */
            border-radius: 5px; /* Border radius 5px */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Efek bayangan (optional) */
        }

        .panel-heading {
            background-color: #5bc0de;
            color: #fff;
            padding: 15px;
            text-align: center;
            border-top-left-radius: 5px; /* Mengatur border-radius sudut kiri atas */
            border-top-right-radius: 5px; /* Mengatur border-radius sudut kanan atas */
        }

        .panel-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-bottom: 15px;
        }

        .btn-info {
            background-color: #5bc0de;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .btn-info:hover {
            background-color: #4298a0;
        }

        .panel-footer {
            background-color: #5bc0de;
            color: #fff;
            padding: 15px;
            text-align: center;
            border-bottom-left-radius: 5px; /* Mengatur border-radius sudut kiri bawah */
            border-bottom-right-radius: 5px; /* Mengatur border-radius sudut kanan bawah */
        }

        .panel-footer a {
            color: #fff;
            text-decoration: none;
        }

        .panel-footer a:hover {
            text-decoration: underline;
        }
    </style>
        </head>
        <body>
            <div class="row">
                <div class="col-md-4 custom-width">
                    <div class="col-md-6">
                        <div class="panel panel-info">
                            <div class="panel-heading"><h3 class="text-center"><b>Kameraku</b></h3></div>
                            <div class="panel-body">
                                <form action="reset_password.php" method="POST">
                                    <input type="hidden" name="token" value="<?= $token ?>">
                                    <div class="form-group">
                                        <label for="new_password">New Password</label>
                                        <input type="password" name="new_password" class="form-control" id="new_password" placeholder="New Password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm Password</label>
                                        <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Password" required>
                                    </div>
                                    <button type="submit" name="reset_password_submit" class="btn btn-info btn-block">Reset Password</button>
                                </form>
                            </div>
                            <div class="panel-footer">
                                <a href="login.php">Back to Login</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </body>
        </html>
        <?php

