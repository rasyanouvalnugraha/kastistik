<?php
include '../kastistik/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf();
include '../kastistik/connection/database.php';

$data = mysqli_query($db, 'SELECT fullname as nama, date as tanggal, amount as pemasukan FROM transactions JOIN users ON transactions.id_user = users.id where type in (1,2);');
ob_start();


?>
<h1>Laporan Transaksi Pemasukan</h1>
<table class="font-bold">
    <thead>
        <tr>
            <td>Nama</td>
            <td>Tanggal</td>
            <td>Pemasukan</td>
        </tr>
    </thead>
    <?php while ($row = mysqli_fetch_assoc($data)) { ?>
        <tr>
            <td><?php echo $row['nama']; ?></td>
            <td><?php echo $row['tanggal']; ?></td>
            <td><?php echo "Rp " . number_format($row['pemasukan'], 0, ',', '.'); ?></td>
        </tr>
    <?php } ?>
</table>



<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12pt;
    }
    h1 {
        font:bold 2em;
        text-align: center;

    }
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }
</style>

<?php
// Tangkap output HTML
$html = ob_get_contents();
ob_end_clean();
$mpdf->WriteHTML($html);
$mpdf->Output("datalaporan_pemasukan.pdf", "I");
?>