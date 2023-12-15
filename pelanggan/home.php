<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="path/to/fancybox.css">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Owl Carousel JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!-- Fancybox JavaScript -->
    <script src="path/to/jquery.fancybox.min.js"></script>
</head>
<style>
    /* CSS untuk elemen pencarian */
    #searchInput {
        margin-top: 20px; /* Sesuaikan margin-top sesuai kebutuhan */
        margin-bottom: 20px; /* Tambahkan margin-bottom untuk jarak ke bawah */
        width: 98%; /* Agar lebar input mencakup seluruh lebar kontainer */
        padding: 20px; /* Sesuaikan padding sesuai kebutuhan */
        box-sizing: border-box; /* Agar padding dan border termasuk dalam lebar dan tinggi elemen */
        border: 1px solid #ccc; /* Warna border dan ketebalan sesuai kebutuhan */
        border-radius: 3px; /* Agar sudut elemen lebih lembut */
        margin-left: 16px;
    }

    /* Penambahan style untuk hasil pencarian */
    .col-xs-6.col-md-3 {
        transition: all 0.3s ease; /* Animasi transisi untuk perubahan display */
        margin-bottom: 20px; /* Tambahkan margin-bottom pada thumbnail */
    }
    #jenisFilter {
    margin-top: 20px;
    margin-right: 10px; /* Sesuaikan margin-right sesuai kebutuhan */
    margin-left: 17px; /* Sesuaikan margin-left sesuai kebutuhan */
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 3px;
    font-size: 14px;
    }

    #merkFilter {
    margin-top: 20px;
    margin-right: 10px; /* Sesuaikan margin-right sesuai kebutuhan */
    margin-left: 17px; /* Sesuaikan margin-left sesuai kebutuhan */
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 3px;
    font-size: 14px;
}


    /* Gaya untuk input pencarian */
    #searchInput {
        margin-top: 20px;
        margin-bottom: 20px;
        width: 98%;
        padding: 20px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-left: 16px;
    }

    /* Gaya untuk hasil pencarian */
    .col-xs-6.col-md-3 {
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    /* Gaya untuk label status kamera */
    .label {
        font-size: 12px;
        margin-top: 5px;
    }

    /* Gaya untuk tombol Sewa */
    .btn-primary {
        background-color: #428bca;
        color: #fff;
        border: 1px solid #357ebd;
        padding: 8px 15px;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        margin-top: 10px;
        cursor: pointer;
    }

    /* Gaya untuk tombol Sewa saat dinonaktifkan */
    .btn-primary[disabled] {
        background-color: #d9edf7;
        color: #31708f;
        border: 1px solid #bce8f1;
        cursor: not-allowed;
    }

    /* Gaya untuk thumbnail kamera */
    .thumbnail {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* Gaya untuk owl carousel */
    .owl-carousel {
        margin-bottom: 20px;
    }

    /* Gaya untuk owl carousel navigation */
    .owl-nav i {
        font-size: 30px;
        color: #428bca;
    }

    /* Gaya untuk owl carousel navigation saat dinonaktifkan */
    .owl-nav [class*=owl-]:hover {
        background-color: transparent;
        color: #428bca;
    }
</style>

<body>
    <!-- Owl Carousel -->
    <div class="owl-carousel">
        <div class="item"><img src="assets/img/a.png" alt="Slide 1" style="width: 100%; height: auto;"></div>
        <div class="item"><img src="assets/img/b.png" alt="Slide 2" style="width: 100%; height: auto;"></div>
        <div class="item"><img src="assets/img/c.png" alt="Slide 3" style="width: 100%; height: auto;"></div>
        <div class="item"><img src="assets/img/d.png" alt="Slide 4" style="width: 100%; height: auto;"></div>
        <div class="item"><img src="assets/img/e.png" alt="Slide 5" style="width: 100%; height: auto;"></div>
    </div>

    <!-- Baris Kamera -->
    <div class="row">
        <!-- Dropdown untuk memilih jenis kamera -->
            <select id="jenisFilter" onchange="filterByJenis()">
                <option value="">Semua Jenis</option>
                <!-- Isi opsi dropdown dengan jenis kamera yang ada -->
                <?php
                $queryJenis = $connection->query("SELECT DISTINCT nama FROM jenis");
                while ($jenis = $queryJenis->fetch_assoc()):
                ?>
                    <option value="<?= $jenis['nama'] ?>"><?= $jenis['nama'] ?></option>
                <?php endwhile; ?>
            </select>
            <!-- Dropdown untuk memilih jenis merk -->
            <select id="merkFilter" onchange="filterByMerk()">
                <option value="">Semua Merk</option>
                <!-- Isi opsi dropdown dengan merk kamera yang ada -->
                <?php
                $queryMerk = $connection->query("SELECT DISTINCT merk FROM kameraku");
                while ($merk = $queryMerk->fetch_assoc()):
                ?>
                    <option value="<?= $merk['merk'] ?>"><?= $merk['merk'] ?></option>
                <?php endwhile; ?>
            </select>

        <!-- Input Pencarian -->
        <input type="text" id="searchInput" placeholder="Cari kamera..." oninput="searchCamera()">

        <?php
        $batasTransaksi = 2; // Tetapkan batas transaksi
        // Sambungkan ke database dan ambil data kamera
        // Saring data sesuai kebutuhan Anda
        $query = $connection->query("SELECT * FROM kameraku JOIN jenis USING(id_jenis)");
        while ($row = $query->fetch_assoc()):
            // Cek apakah pelanggan telah melebihi batas transaksi
            $idPelanggan = isset($_SESSION['id_pelanggan']) ? $_SESSION['id_pelanggan'] : null;
            $queryTransaksi = $connection->prepare("SELECT COUNT(*) as jumlah FROM transaksi WHERE id_pelanggan = ?");
            $queryTransaksi->bind_param("i", $idPelanggan);
            $queryTransaksi->execute();
            $resultTransaksi = $queryTransaksi->get_result();
            $jumlahTransaksiPelanggan = $resultTransaksi->fetch_assoc()['jumlah'];

            // Nonaktifkan tombol jika pelanggan telah melebihi batas transaksi
            $isNonaktif = ($jumlahTransaksiPelanggan >= $batasTransaksi) ? "disabled" : "";
        ?>

            <div class="col-xs-6 col-md-3">
                <!-- Kamera Thumbnail -->
                <div class="thumbnail">
                    <a href="assets/img/kamera/<?=$row['gambar']?>" class="fancybox">
                        <img src="assets/img/kamera/<?=$row['gambar']?>" alt="<?=$row['nama_kamera']?>" style="height: 200px;">
                    </a>
                    <div class="caption text-center">
                        <!-- Informasi Kamera -->
                        <h4><?=$row["nama_kamera"]?></h4>
                        <h5>Rp.<?=$row["harga"]?>,- <?=$row["nama"]?> - <?=$row["merk"]?></h5>
                        <h6><?=$row["no_kamera"]?></h6>
                        <span class="label label-<?=($row['status']) ? "success" : "danger" ?>">
                            <?=($row['status']) ? "Tersedia" : "Tidak Tersedia" ?>
                        </span>
                        <p>
                            <!-- Tombol Sewa -->
                            <br>
                            <a href="<?=($row['status'] && !$isNonaktif) ? "?page=transaksi&id=$row[id_kameraku]" : "#" ?>" class="btn btn-primary" <?=($row['status'] && !$isNonaktif) ? "" : "disabled" ?>>Sewa Sekarang!</a>
                        </p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Fancybox Script -->
    <script type="text/javascript">
        $(document).ready(function(){
            $(".fancybox").fancybox({
                openEffect  : 'none',
                closeEffect : 'none',
                iframe : {
                    preload: false
                }
            });
        });
    </script>

    <!-- Owl Carousel Script -->
    <script type="text/javascript">
       $(document).ready(function(){
          $(".owl-carousel").owlCarousel({
             items: 1,
             autoplay: true,
             autoplayTimeout: 5000,
             loop: true,
             nav: true,
             navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
          });
       });
    </script>

    <!-- Fungsi Pencarian JavaScript -->
    <script type="text/javascript">
        function searchCamera() {
            var input, filter, cameras, camera, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            cameras = document.querySelectorAll('.col-xs-6.col-md-3');

            cameras.forEach(function (camera) {
                var cameraInfo = camera.textContent || camera.innerText;
                if (cameraInfo.toUpperCase().indexOf(filter) > -1) {
                    camera.style.display = "";
                } else {
                    camera.style.display = "none";
                }
            });
        }
        function filterByJenis() {
            var selectedJenis = document.getElementById('jenisFilter').value.toUpperCase();
            var cameras = document.querySelectorAll('.col-xs-6.col-md-3');

            cameras.forEach(function (camera) {
                var jenisKamera = camera.querySelector('h5').textContent.toUpperCase();
                if (selectedJenis === '' || jenisKamera.indexOf(selectedJenis) > -1) {
                    camera.style.display = "";
                } else {
                    camera.style.display = "none";
                }
            });
        }
        

    </script>
    <script type="text/javascript">
    function filterByMerk() {
        var selectedMerk = document.getElementById("merkFilter").value.toUpperCase();
        var cameras = document.querySelectorAll('.col-xs-6.col-md-3');

        cameras.forEach(function (camera) {
            var merkKamera = camera.querySelector('h5').textContent.toUpperCase();
            if (selectedMerk === '' || merkKamera.indexOf(selectedMerk) > -1) {
                camera.style.display = "";
            } else {
                camera.style.display = "none";
            }
        });
    }
</script>

    
</body>

</html>
