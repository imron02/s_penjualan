<h1 class="sub-judul display">Invoice</h1>

<?php 
error_reporting(0);
include_once "connection.php";
$id_pelanggan;
	$id_transaksi = isset($_GET['id_transaksi']) && (int) $_GET['id_transaksi'] > 0 ? $_GET['id_transaksi'] : FALSE;
	if(!$id_transaksi):
?>

	
	<table>
		<thead>
			<tr>
				<th>Id Transaksi</th>
				<th>Tanggal</th>
				<th>Nama Pelanggan</th>
				<th>Pengaturan</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			$dataTransaksi = mysql_query("
					SELECT 
						* 
					FROM 
						transaksi t
					LEFT JOIN
						pelanggan p ON p.id_pelanggan=t.id_pelanggan 
					WHERE 
						t.tipe_transaksi = 'penjualan'
					ORDER BY
						t.tanggal_transaksi DESC
					");
			
			while($o = mysql_fetch_object($dataTransaksi)):
			
				echo "
						<tr>
							<td>{$o->id_transaksi}</td>
							<td>".strtok($o->tanggal_transaksi,' ')."</td>
							<td>{$o->nama_pelanggan}</td>
							<td>
								<a href=\"?halaman=invoice&id_transaksi={$o->id_transaksi}\">Rincian Invoice</a>
							</td>
						</tr>";
			
			endwhile;?>
		</tbody>
	</table>
	
<?php else:?>
<?php
//// >>>F was here
if ($_POST['act']=="update"):
	$id_transaksi = $_GET['id_transaksi'];
	if ($_GET['id_transaksi'] != ""):
		if(count($_POST['kode_barang']) > 1) {
			for ($i = 0; $i < count($_POST['kode_barang']); $i++) {
				$q_tr = "UPDATE transaksi_rinci 
						SET harga_saatini='".str_replace(',', '', $_POST['harga_saatini'][$i])."',
						id_pelanggan='".$_POST['id_pelanggan']."',
						updated='".date("Y-m-d")."'
						WHERE kode_barang='".$_POST['kode_barang'][$i]."'
						AND id_transaksi = $id_transaksi";
				mysql_query($q_tr);
			}
		} else {
			$q_tr = "UPDATE transaksi_rinci 
					SET harga_saatini='".str_replace(',', '', $_POST['harga_saatini'][0])."'
					WHERE kode_barang='".$_POST['kode_barang'][0]."'
					AND id_transaksi = $id_transaksi";
			mysql_query($q_tr);
		}

		$diskon_keseluruhan = str_replace(",", "", $_POST['discount_total']);
		$ppn = str_replace(",", "", $_POST['ppn']);
		$pembayaran  = str_replace(",", "", $_POST['pembayaran']);
		$tunggakan  = str_replace(",", "", $_POST['tunggakan']);
		$jatuh_tempo = $_POST['jatuh_tempo'];
		$grand_total = str_replace(",", "", $_POST['grand_total']);
		$id_pelangganupdate = $_POST['id_pelanggan'];
		$query = "UPDATE transaksi SET id_pelanggan = $id_pelangganupdate,diskon_keseluruhan=$diskon_keseluruhan,ppn=$ppn,pembayaran=$pembayaran,tunggakan=$tunggakan,jatuh_tempo='$jatuh_tempo', grand_total=$grand_total WHERE id_transaksi='$id_transaksi';";
		mysql_query($query);
?>
<script type="text/javascript" language="Javascript">
	window.open('cetakinvoice.php?id_transaksi=<?php echo($id_transaksi); ?>');
	//alert("<?php echo($query); ?>");
</script>
<?php endif; endif; ?>
<form method="POST">
<p class="display">
	<a href="?halaman=invoice" class="tombol">&laquo; Kembali ke daftar invoice</a>
	<button class="tombol" type="submit" name="act" value="update">Simpan dan Cetak</button>
</p>
<p class="display-penjualan-hide" style="display: none;">
	<a href="?halaman=laporan&jenis_laporan=penjualan" class="tombol">&laquo; Kembali ke laporan penjualan</a>
</p>
<p class="display-piutang-hide" style="display:none;">
	<a href="?halaman=laporan&jenis_laporan=piutang" class="tombol">&laquo; Kembali ke laporan piutang</a>
</p>
<?php 
	$dataTransaksi = mysql_query("
					SELECT
						*
					FROM
						transaksi t
					LEFT JOIN
						pelanggan p 
					ON 
						p.id_pelanggan=t.id_pelanggan
					WHERE
						t.id_transaksi = '{$_GET['id_transaksi']}'
					");
	$D = (object) mysql_fetch_assoc($dataTransaksi);
	$id_pelanggan = $D->id_pelanggan;
	//>>> F was here
	$diskon_keseluruhan = $D->diskon_keseluruhan;
	$ppn = $D->ppn;;
	$pembayaran  = $D->pembayaran;
	$tunggakan  = $D->tunggakan;
?>
<table>
	<thead>
		<tr>
			<th colspan="2">Detail Invoice</th>
		</tr>
		<tr>
			<th align="right">Tanggal :</th>
			<th align="left"><?php echo strtok($D->tanggal_transaksi,' ');?></th>
		</tr>
		<tr>
			<th align="right">ID Transaksi :</th>
			<th align="left"><?php echo $D->id_transaksi;?></th>
		</tr>
		<tr>
			<th align="right" width="200">Nama Penerima :</th>
			<th align="left"><?php echo $D->nama_pelanggan;?></th>
		</tr>
	</thead>
</table>
<hr />
<table id="barang">
	<thead>
		<tr>
			<th>Kode</th>
			<th>Nama</th>
			<th>Satuan</th>
			<th>Jumlah Barang</th>
			<th>Diskon</th>
			<th>Harga Barang</th>
			<th>Subtotal</th>
		</tr>
	</thead>
	<tbody>
		<?php  
		$Q = mysql_query("
				SELECT
					t.*,b.nama_barang,tb.*
				FROM
					transaksi_rinci t
				LEFT JOIN 
					barang b ON b.kode_barang = t.kode_barang
				LEFT JOIN 
					tipe_barang tb ON tb.id_tipe_barang = b.id_tipe_barang
				WHERE
					t.id_transaksi = '{$_GET['id_transaksi']}'
				"); 
		$keseluruhan = 0;
		$totalDiskon = 0;
		while($o = mysql_fetch_object($Q)):
		$totalDiskon += $o->diskon;
		$total = $o->banyaknya*$o->harga_saatini;
		$potonganDiskon = ($total * $o->diskon) / 100;
		$subTotal = $total - $potonganDiskon;
		$keseluruhan+=$subTotal;
		?>
			<tr>
				<td>
					<?php echo $o->kode_barang ?>
					<input type="hidden" name="kode_barang[]" value="<?php echo $o->kode_barang ?>" />
					<input type="hidden" name="id_pelanggan" value="<?php echo $id_pelanggan ?>" />
				</td>
				<td><?php echo $o->nama_barang ?></td>
				<td><?php echo $o->nama_tipe_barang ?></td>
				<td class="banyaknya"><?php echo $o->banyaknya ?></td>
				<td align='right'><?php echo $o->diskon ?> %</td>
				<td align='right'><label class='kiri'>Rp.</label>
					<input type="text" name="harga_saatini[]" class="harga_saatini kanan" value="<?php echo number_format($o->harga_saatini,0,'.',','); ?>" size="9" />
				</td>
				<td align='right'>
					<label class='kiri'>Rp.</label> 
					<div class="subtotal"><?php echo number_format($subTotal,0,'.',',') ?></div>
				</td>
			</tr>
		<?php
			$mySql = "SELECT * FROM history_harga WHERE kode_barang = '$o->kode_barang'";
			
			$mySql = "INSERT INTO history_harga (id_pelanggan, kode_barang, barang, harga_barang, tanggal) values ('$id_pelanggan', '$o->kode_barang', '$o->nama_barang','$subTotal',NOW())";
		mysql_query($mySql, $koneksidb) or die ("Query salah : ".mysql_error());
		endwhile;
		$discount_total_nominal = $keseluruhan * $diskon_keseluruhan / 100;
		$ppn_nominal = ($keseluruhan - $discount_total_nominal) * $ppn / 100;
		$grand_total = $keseluruhan - $discount_total_nominal + $ppn_nominal;
		?>
	</tbody>
	<link rel="stylesheet" href="./publik/tema/css/ui-darkness/jquery-ui-1.10.4.custom.min.css"/>
	<script src="./publik/javascript/jquery-1.10.2.min.js"></script>
	<script src="./publik/javascript/jquery-ui.js"></script>
	<script src="./publik/javascript/jquery.number.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$('.date')
			 .attr({'placeholder':'yyyy-mm-dd','readonly':'readonly'})
			 .datepicker({
				dateFormat:'yy-mm-dd',
				showAnim: "fold",
				changeMonth: true,
				changeYear: true
			});
		});
	</script>
	<tfoot>
		<tr>
			<th colspan="5">Jatuh Tempo pada : <input type="text" class="date" name="jatuh_tempo" id="jatuh_tempo" value="<?php echo ($D->jatuh_tempo) ?>"/></th>
			<th align="right">Total Harga</th>
			<th align="right"><label class='kiri'>Rp.</label><input type="text" id="keseluruhan" name="keseluruhan" value="<?php echo $keseluruhan ?>" size="9" class="kanan" readonly="readonly"></th>
		</tr>
		<tr>
			<th colspan="4"></th>
			<th align="right">Discount</th>
			<th align="right"><label class="kanan">%</label><input class="kanan" type="text" id="discount_total" name="discount_total" size="7" onkeyup="calculate();" value="<?php echo $diskon_keseluruhan ?>"></th>
			<th align="right"><label class='kiri'>Rp.</label><input class="kanan" type="text" id="discount_total_nominal" name="discount_total_nominal" size="9"  value="<?php echo(number_format($discount_total_nominal,0,'.',',')) ?>" readonly></th>
		</tr>
		<tr>
			<th align="left" colspan="4">Mengetahui / Menyetujui</th>
			<th align="right">PPN</th>
			<th align="right"><label class="kanan">%</label><input type="text" class="kanan" id="ppn" name="ppn" size="7" onkeyup="calculate();"  value="<?php echo(number_format($ppn,0,'.',',')) ?>"></th>
			<th align="right"><label class='kiri'>Rp.</label><input type="text" class="kanan" id="ppn_nominal" name="ppn_nominal" size="9"  value="<?php echo(number_format($ppn_nominal,0,'.',',')) ?>" readonly></th>
		</tr>
		<tr>
			<th colspan="4"></th>
			<th align="right"></th>
			<th align="right" colspan="2"><hr></th>
		</tr>
		<tr>
			<th colspan="4"></th>
			<th align="right" colspan="2">Grand Total</th>
			<th align="right"><label class='kiri'>Rp.</label><input class="kanan" type="text" id="grand_total" name="grand_total" size="9"  value="<?php echo(number_format($grand_total,0,'.',',')) ?>" readonly></th>
		</tr>
		<tr>
			<th colspan="4"></th>
			<th align="right" colspan="2">Pembayaran</th>
			<th align="right"><label class='kiri'>Rp.</label><input class="kanan" type="text" id="pembayaran" name="pembayaran" size="9" onkeyup="calculate();" value="<?php echo(number_format($pembayaran,0,'.',',')); ?>"></th>
		</tr>

		<tr>
			<th align="left" colspan="4">(................................................)</th>
			<th align="right" colspan="2">Tunggakan</th>
			<th align="right"><label class='kiri'>Rp.</label><input class="kanan" type="text" id="tunggakan" name="tunggakan" size="9" readonly  value="<?php echo(number_format($tunggakan,0,'.',',')); ?>"></th>
		</tr>
	</tfoot>
</table>
</form>
<?php endif;?>

<script>
	$(function() {
		var halaman = "<?php echo $_GET['laporan'] ?>";
		if(halaman == "detail_penjualan") {
			$(".harga_saatini,#ppn,#discount_total,#pembayaran").prop('disabled', true);
			$(".date").datepicker('disable');
			$(".display").css("display", "none");
			$(".display-penjualan-hide").css("display","");
		}

		if(halaman == "detail_piutang") {
			$(".harga_saatini,#ppn,#discount_total,#pembayaran").prop('disabled', true);
			$(".date").datepicker('disable');
			$(".display").css("display", "none");
			$(".display-piutang-hide").css("display","");
		}

		console.log(halaman);

		$(".harga_saatini,#keseluruhan,#grand_total,#tunggakan,#discount_total_nominal,#ppn_nominal,#pembayaran").number(true);
		$(".harga_saatini").keyup(function() {
			var harga_saatini = $(this).val();
			var jumlah_barang = $(this).closest("tr").find(".banyaknya").text();
			$(this).closest("tr").find(".subtotal").empty().append($.number(harga_saatini * jumlah_barang));

			calculate();
		});
	});

	function calculate() {
		var sum = 0;
		$("#barang td").find(".subtotal").each(function() {
			sum += parseInt($(this).text().replace(/,/g, ''));
		});
		$("#keseluruhan").val(sum);

		var total_harga = $("#keseluruhan").val();
		var discount_total = $("#discount_total").val();
		var discount_total_nominal = total_harga * discount_total / 100;
		$("#discount_total_nominal").val(discount_total_nominal);

		var ppn = $("#ppn").val();
		var ppn_nominal = ppn * (total_harga - discount_total_nominal) / 100;
		$("#ppn_nominal").val(ppn_nominal);

		var grand_total = total_harga - discount_total_nominal + ppn_nominal;
		$("#grand_total").val(grand_total);
		console.log(grand_total);

		var pembayaran = $("#pembayaran").val();
		var tunggakan = grand_total - pembayaran;
		$("#tunggakan").val(tunggakan);
	}
</script>