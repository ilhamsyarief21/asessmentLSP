<?php
require 'vendor/autoload.php'; // Update the path to your Composer autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['forgot_submit'])) {
        $email = $_POST['email'];

        $query = $connection->prepare("SELECT * FROM pelanggan WHERE email = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $token = bin2hex(random_bytes(16));

            $updateQuery = $connection->prepare("UPDATE pelanggan SET reset_token = ? WHERE email = ?");
            $updateQuery->bind_param("ss", $token, $email);
            $updateQuery->execute();

            // Send email using PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // Sesuaikan dengan SMTP server Anda
                $mail->SMTPAuth   = true;
                $mail->Username   = 'lupap885@gmail.com'; // Sesuaikan dengan SMTP username Anda
                $mail->Password   = 'fdrukikpbtngefwv'; // Sesuaikan dengan SMTP password Anda
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                // Recipients
                $mail->setFrom('kameraku@gmail.com', 'Kameraku!');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Reset Password - Kameraku';
                $mail->Body    = "Halo!\n\nKami menerima permintaan reset password untuk akun Anda. Klik link berikut untuk mereset password Anda:\n\nhttp://localhost:8080/kamerakuLSP/reset_password.php?token=$token\n\nJika Anda tidak merasa melakukan permintaan ini, abaikan email ini.";

                $mail->send();
                echo alert("Email reset password telah dikirim. Silakan cek email Anda.", "login.php");
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo alert("Email tidak terdaftar.", "forgot_password.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kameraku - Forgot Password</title>
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
                        <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                            </div>
                            <button type="submit" name="forgot_submit" class="btn btn-info btn-block">Reset Password</button>
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
