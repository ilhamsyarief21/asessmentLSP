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
        margin-top: 30px; /* Sesuaikan margin-top sesuai kebutuhan */
        width: 98%; /* Agar lebar input mencakup seluruh lebar kontainer */
        padding: 20px; /* Sesuaikan padding sesuai kebutuhan */
        box-sizing: border-box; /* Agar padding dan border termasuk dalam lebar dan tinggi elemen */
        border: 2px solid #ccc; /* Warna border dan ketebalan sesuai kebutuhan */
        border-radius: 5px; /* Agar sudut elemen lebih lembut */
        margin-left: 16px;
    }

    /* Penambahan style untuk hasil pencarian */
    .col-xs-6.col-md-3 {
        transition: all 0.3s ease; /* Animasi transisi untuk perubahan display */
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
    </script>
</body>

</html>
