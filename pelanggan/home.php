<!-- Owl Carousel CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

<!-- Owl Carousel JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<style>
	
</style>

<div class="owl-carousel">
    <div class="item"><img src="assets/img/a.png" alt="Slide 1" style="width: 100%; height: auto;"></div>
    <div class="item"><img src="assets/img/b.png" alt="Slide 2" style="width: 100%; height: auto;"></div>
    <div class="item"><img src="assets/img/c.png" alt="Slide 3" style="width: 100%; height: auto;"></div>
    <div class="item"><img src="assets/img/d.png" alt="Slide 4" style="width: 100%; height: auto;"></div>
    <div class="item"><img src="assets/img/e.png" alt="Slide 5" style="width: 100%; height: auto;"></div>
</div>



<!-- Baris Kamear -->
<div class="row">
    <?php
    $query = $connection->query("SELECT * FROM kameraku JOIN jenis USING(id_jenis)");
    while ($row = $query->fetch_assoc()):
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
                        <a href="<?=($row['status']) ? "?page=transaksi&id=$row[id_kameraku]" : "#" ?>" class="btn btn-primary" <?=($row['status']) ?: "disabled" ?>>Sewa Sekarang!</a>
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
        $(".various").fancybox({
            maxWidth    : 800,
            maxHeight   : 600,
            fitToView   : false,
            width       : '70%',
            height      : '70%',
            autoSize    : false,
            closeClick  : false,
            openEffect  : 'none',
            closeEffect : 'none'
        });
        $('.fancybox-media').fancybox({
            openEffect  : 'none',
            closeEffect : 'none',
            helpers : {
                media : {}
            }
        });
    });
</script>

<script type="text/javascript">
   $(document).ready(function(){
      $(".owl-carousel").owlCarousel({
         items: 1,
         autoplay: true,
         autoplayTimeout: 5000, // Ganti dengan interval yang diinginkan (dalam milidetik)
         loop: true,
         nav: true,
         navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
      });
   });
</script>
