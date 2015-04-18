<?php 
ob_start();
require_once "dompdf/dompdf_config.inc.php";

mysql_connect("localhost","root","password");
mysql_select_db("s_penjualan");
//require ("fungsi/html2pdf/html2pdf.class.php");
//$content = ob_get_clean();
$query = "SELECT 
				* 
			FROM 
				transaksi t
			LEFT JOIN
				pelanggan p ON p.id_pelanggan=t.id_pelanggan 
			WHERE 
				t.tipe_transaksi = 'penjualan' AND
				t.id_transaksi = '".$_GET['id_transaksi']."';";
$result = mysql_query($query);
$data_transaksi = mysql_fetch_assoc($result);
$query = "SELECT
					t.*,b.nama_barang,tb.*
				FROM
					transaksi_rinci t
				LEFT JOIN 
					barang b ON b.kode_barang = t.kode_barang
				LEFT JOIN 
					tipe_barang tb ON tb.id_tipe_barang = b.id_tipe_barang
				WHERE
					t.id_transaksi = '".$_GET['id_transaksi']."'";
$result = mysql_query($query);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">
	<style>
		body {
			font-family: Courier;
			/*margin-top: -5%;
			font-size: 13px;
			font-weight: bold;
			line-height: 90%;	*/
			letter-spacing: 1px;
			font-weight: bold;
			line-height: 90%;
			font-size: 9px;
		}

		.center {
			text-align: center;
		}

		.kiri {
			text-align: left;
		}

		.kanan {
			text-align: right;
			margin-top: -7px;
		}
	</style>
</head>
<body>
	PT.WECO BINA UTAMA<br />
	Jl.Roa Malaka II No.10-JAKARTA BARAT<br />
	Telp. 021-6901655<br />
	Fax. 021-6929219<br />
	<?php echo str_repeat("&nbsp;", 90) ?>FAKTUR PENJUALAN<br />
	ID Transaksi      : <?php echo $data_transaksi['id_transaksi'] ?><br />
	Tanggal Transaksi : <?php echo $data_transaksi['tanggal_transaksi'] ?><br />
	Pelanggan         : <?php echo $data_transaksi['nama_pelanggan'] ?><br />
	<table width="47%">
		<thead>
			<tr>
				<td colspan="8"><?php echo str_repeat("-", 109) ?><br /></td>
			</tr>
			<tr>
				<td width="1%">No.</td>
				<td width="5%">No. Item</td>
				<td width="15%">Nama Barang</td>
				<td width="3%">Qty</td>
				<td width="5%">Satuan</td>
				<td width="1%">Disc</td>
				<td width="6%" class="center">Harga</td>
				<td width="10%" class="center">Total</td>
			</tr>
			<tr>
				<td colspan="8"><?php echo str_repeat("-", 109) ?><br /></td>
			</tr>
		</thead>
		<tbody>
		<?php 
			$keseluruhan = 0;
			$totalDiskon = 0;
			$i = 0;
			while ($data_rinci = mysql_fetch_assoc($result)):
				$total = $data_rinci['banyaknya']*$data_rinci['harga_saatini'];
				$potonganDiskon = ($total * $data_rinci['diskon']) / 100;
				$subTotal = $total - $potonganDiskon;
				$keseluruhan+=$subTotal;
			?>
			<tr>
				<td><?php echo $i + 1 ?></td>
				<td><?php echo $data_rinci['kode_barang'] ?></td>
				<td><?php echo $data_rinci['nama_barang'] ?></td>
				<td><?php echo $data_rinci['banyaknya'] ?></td>
				<td><?php echo $data_rinci['nama_tipe_barang'] ?></td>
				<td><?php echo $data_rinci['diskon'] ?>%</td>
				<td>
					<label class="kiri">Rp. </label>
					<div class="kanan"><?php echo number_format($data_rinci['harga_saatini'],0,',','.') ?></div>
				</td>
				<td>
					<label class="kiri">Rp. </label>
					<div class="kanan"><?php echo number_format($subTotal,0,',','.') ?></div>
				</td>
			</tr>
		<?php $i++; endwhile; ?>
		</tbody>
	</table>
	<?php 
	$diskon_keseluruhan_nominal = $keseluruhan * $data_transaksi['diskon_keseluruhan'] / 100;
	$ppn_nominal = ($keseluruhan - $diskon_keseluruhan_nominal) * $data_transaksi['ppn'] / 100;
	$grand_total = $keseluruhan - $diskon_keseluruhan_nominal + $ppn_nominal;

	$discount = $data_transaksi['diskon_keseluruhan'];
	$discountTotal = number_format($diskon_keseluruhan_nominal,0,',','.');
	$ppn = $data_transaksi['ppn'];
	$ppnTotal = number_format($ppn_nominal,0,',','.');
	$grandTotal = number_format($grand_total,0,',','.');
	$pembayaran = number_format($data_transaksi['pembayaran'],0,',','.');
	$tunggakan = number_format($data_transaksi['tunggakan'],0,',','.');

	$jatuhTempo = "Jatuh Tempo:".$data_transaksi['jatuh_tempo'];
	$keseluruhan = number_format($keseluruhan,0,',','.');
	?>
	<?php echo str_repeat("-", 110) ?><br />
	<table width="47%">
		<tr>
			<td width="30.5%"><?php echo $jatuhTempo ?></td>
			<td width="8%">Total  :</td>
			<td width="8.5%">
				<label class="kiri">Rp. </label>
				<div class="kanan"><?php echo $keseluruhan ?></div>
			</td>
		</tr>
		<tr>
			<td>Mengetahui/Menyetujui</td>
			<td>Discount  : <?php echo $discount?>%</td>
			<td>
				<label class="kiri">Rp. </label>
				<div class="kanan"><?php echo $discountTotal?></div>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>PPN  : <?php echo $ppn ?>%</td>
			<td>
				<label class="kiri">Rp. </label>
				<div class="kanan"><?php echo $ppnTotal?></div>
			</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2"><?php echo str_repeat("-", 38) ?></td>
		</tr>
		<tr>
			<td></td>
			<td>Grand Total  :</td>
			<td>
				<label class="kiri">Rp. </label>
				<div class="kanan"><?php echo $grandTotal ?></div>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>Pembayaran  :</td>
			<td>
				<label class="kiri">Rp. </label>
				<div class="kanan"><?php echo $pembayaran ?></div>
			</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2"><?php echo str_repeat("-", 38) ?></td>
		</tr>
		<tr>
			<td>(<?php echo str_repeat("...", 12) ?>)</td>
			<td>Tunggakan  :</td>
			<td>
				<label class="kiri">Rp. </label>
				<div class="kanan"><?php echo $tunggakan?></div>
			</td>
		</tr>
	</table>
</body>
</html>
<?php
$dompdf = new DOMPDF();
$dompdf->load_html(ob_get_clean());
// $dompdf->set_paper("letter", "landscape");
// $dompdf->set_paper(array(0,0,595.28,841.89));
$dompdf->set_paper(array(0,-20,1200,420.945));
$dompdf->render();
$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));

?>