<?php
if (!isset($_SESSION["pelanggan"])) {
  header('location: login.php');
  exit;
}
?>

<div class="panel panel-info">
    <div class="panel-heading"><h3 class="text-center">Sewa</h3></div>
    <div class="panel-body">
        <form action="?page=selesai" method="POST">
            <input type="hidden" name="id_mobil" value="<?=$_GET["id"]?>">
            <div class="form-group">
                <label for="lama">Lama Sewa</label>
                <select name="lama" class="form-control">
                    <?php for ($i=1; $i<=7; $i++): ?>
                      <option value="<?=$i?>"><?=$i?> Hari</option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Ambil</label>
                <!-- <input type="text" name="ambil" class="form-control"> -->
                <div class="row">
                  <div class="col-md-4">
                  <select name="thn" id="thn" class="form-control" required="on">
                    <option>-- Tahun --</option>
                    <?php
                    $currentYear = date('Y');
                    for ($i = $currentYear; $i <= $currentYear + 2; $i++):
                    ?>
                        <option value="<?=$i?>"><?=$i?></option>
                    <?php endfor; ?>
                </select>
                  </div>
                  <div class="col-md-4">
                  <select name="bln" id="bln" class="form-control" required="on">
                      <option>-- Bulan --</option>
                  </select>
                </div>
                  <div class="col-md-4">
                    <select name="tgl" class="form-control" required="on">
                        <option>-- Tanggal --</option>
                        <?php
                        $currentYear = date('Y');
                        $currentMonth = date('n');
                        $maxDays = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

                        for ($i = 1; $i <= $maxDays; $i++) {
                            echo "<option value=\"$i\">$i</option>";
                        }

                        if ($currentMonth < 12) {
                            $nextMonthDays = cal_days_in_month(CAL_GREGORIAN, $currentMonth + 1, $currentYear);
                            for ($j = 1; $j <= $nextMonthDays; $j++) {
                                echo "<option value=\"$j\">$j</option>";
                            }
                        }
                        ?>
                    </select>
                  </div>
                </div>
            </div>
            <div class="form-group">
                <label for="status">Pakai supir?</label>
                <?php $query = $connection->query("SELECT id_supir FROM supir WHERE status='1' LIMIT 1"); if ($query->num_rows == 0): ?>
                  <input type="text" class="form-control" disabled value="Maaf saat ini supir belum tersedia...">
                  <input type="hidden" name="status" value="0">
                <?php else: ?>
                  <select name="status" class="form-control">
                      <option value="1">Ya</option>
                      <option value="0">Tidak</option>
                  </select>
               <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="jaminan">Jaminan</label>
                <select name="jaminan" class="form-control">
                    <option value="STNK">STNK</option>
                    <option value="Sertifikat Rumah">Sertifikat Rumah</option>
                </select>
            </div>
            <button type="submit" class="btn btn-info btn-block">NEXT!</button>
        </form>
    </div>
</div>


<script>
document.getElementById('thn').addEventListener('change', function() {
    var selectedYear = this.value;
    var currentYear = new Date().getFullYear();
    var maxMonth = (selectedYear == currentYear) ? new Date().getMonth() + 1 : 12;

    var selectBln = document.getElementById('bln');
    selectBln.innerHTML = '';

    for (var i = 1; i <= maxMonth; i++) {
        var option = document.createElement('option');
        option.value = i;
        option.text = i;
        selectBln.appendChild(option);
    }
});
</script>