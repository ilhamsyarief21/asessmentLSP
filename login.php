<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once "config.php";
    $sql = "SELECT * FROM pelanggan WHERE username='$_POST[username]' AND password='" . md5($_POST['password']) . "'";
    if ($query = $connection->query($sql)) {
        if ($query->num_rows) {
            session_start();
            while ($data = $query->fetch_array()) {
                $_SESSION["pelanggan"]["is_logged"] = true;
                $_SESSION["pelanggan"]["id"] = $data["id_pelanggan"];
                $_SESSION["pelanggan"]["username"] = $data["username"];
                $_SESSION["pelanggan"]["nama"] = $data["nama"];
                $_SESSION["pelanggan"]["no_ktp"] = $data["no_ktp"];
                $_SESSION["pelanggan"]["no_telp"] = $data["no_telp"];
                $_SESSION["pelanggan"]["email"] = $data["email"];
                $_SESSION["pelanggan"]["alamat"] = $data["alamat"];
              }
            header('location: index.php');
        } else {
            echo alert("Username / Password tidak sesuai!", "login.php");
        }
    } else {
        echo "Query error!";
    }
}
// Check if "Forgot Password" link is clicked
if (isset($_POST['forgot_password'])) {
    header('location: forgot_password.php');
    exit();
}
?>
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kameraku</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Add Poppins font from Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <style>
        body {
            margin-top: 40px;
            background-image: url(assets/img/bb.jpg);
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: 'Poppins', sans-serif; /* Apply Poppins font to the entire body */
        }

        .custom-width {
            width: 150%;
            height: 100%;
            margin-right: 60px; /* Adjust the margin as needed */
            margin-left: 130px; /* Adjust the margin as needed */
        }

        .panel {
            width: 100%;
        }

        .panel-heading {
            background-color: #5bc0de; /* Updated color: Green */
            color: #fff;
        }

        .panel-footer {
            background-color: #5bc0de; /* Updated color: Blue */
            color: #fff;
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 custom-width">
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading"><h3 class="text-center"><b>Kameraku</b></h3></div>
                        <div class="panel-body">
                            <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" class="form-control" id="username" placeholder="Username" autofocus="on">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                                </div>
                                <button type="submit" class="btn btn-info btn-block">Login</button>
                                <a href="#" onclick="redirectToForgotPassword()" class="btn btn-link btn-block">Forgot Password?</a>
                                    <script>
                                        function redirectToForgotPassword() {
                                            window.location.href = 'forgot_password.php';
                                        }
                                    </script>


                                
                            </form>
                        </div>
                        <div class="panel-footer">
                            Belum punya akun? <a href="index.php?page=daftar">Daftar sekarang.</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
</body>
</html>
