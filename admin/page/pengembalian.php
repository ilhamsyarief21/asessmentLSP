<?php
// Assuming $connection is your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return_code'])) {
    $return_code = $connection->real_escape_string($_POST['return_code']);

    // Get the relevant information from the database based on the return code
    $result = $connection->query("SELECT * FROM transaksi WHERE kode='$return_code'");
    
    if ($result->num_rows > 0) {
        $transaction = $result->fetch_assoc();

        // Get the current date and time
        $current_datetime = date('Y-m-d H:i:s');

        // Update the transaction with return date and time
        $connection->query("UPDATE transaksi SET tgl_kembali='$current_datetime' WHERE kode='$return_code'");

        // You can perform additional actions or calculations here if needed

        echo alert("Berhasil mengembalikan kamera dengan kode $return_code", "?page=pengembalian");
    } else {
        echo alert("Kode transaksi tidak valid", "?page=pengembalian");
    }
}

?>
<div class="panel panel-info">
            <div class="panel-heading"><h3 class="text-center">PENGEMBALIAN KAMERA</h3></div>
            <div class="panel-body">
                <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
                    <div class="form-group">
                        <label for="return_code">Kode Transaksi</label>
                        <input type="text" name="return_code" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-info btn-block">Kembalikan Kamera</button>
                </form>
            </div>
        </div>