<?php
error_reporting(0);
?>

<h1 class="sub-judul">Laporan <?php echo ucfirst($_GET['jenis_laporan'])?></h1>

	<link rel="stylesheet" href="./publik/tema/css/ui-darkness/jquery-ui-1.10.4.custom.min.css"/>
	<script src="./publik/javascript/jquery-1.10.2.min.js"></script>
	<script src="./publik/javascript/jquery-ui.js"></script>
	<form class="formulir-umum display" method="get" action="./">
		<fieldset>
			<legend>Pencarian data laporan <?php echo $_GET['jenis_laporan'];?> : </legend>
			<label>Mulai Tanggal : </label>
			<input type="text" class="date" name="mulai" value="<?php echo date("Y-m-d")?>"/>
			<label>Sampai Tanggal : </label>
			<input type="text" class="date-end" name="sampai" value="<?php echo date("Y-m-d",strtotime("+31 day",time()))?>"/>
			<label>Pelanggan :</label>
			<select name="pelanggan">
				<option value="semua">Semua</option>
				<?php
					$f = mysql_query("SELECT * FROM pelanggan");
					while($c = mysql_fetch_object($f)):
					
						echo '<option value="'.$c->id_pelanggan.'">'.$c->nama_pelanggan.'</option>';
					
					endwhile;
				?>
			</select>
			<input type="hidden" name="halaman" value="laporan"/>
			<input type="hidden" name="jenis_laporan" value="<?php echo $_GET['jenis_laporan'];?>"/>
			<hr/>
			<button class="tombol" type="submit">Cari</button>
		</fieldset>
	</form>
	<script>
		 $('.date')
		 .attr({'placeholder':'yyyy-mm-dd','readonly':'readonly'})
		 .datepicker({
				 minDate : '-1440',
				 dateFormat:'yy-mm-dd',
				 showAnim: "fold",
				 changeMonth: true,
				 changeYear: true,
				 onClose: function( a ) 
				 {
					 $(".date-end").datepicker( "option", "minDate", a );
				 }
         });
		
		$('.date-end')
        .attr({'placeholder':'yyyy-mm-dd','readonly':'readonly'})
        .datepicker({
                dateFormat:'yy-mm-dd',
                defaultDate: "+1m",
                showAnim: "fold",
                changeMonth: true,
                changeYear: true,
                onClose: function( a ) 
                    {
                        $(".date").datepicker( "option", "maxDate", a );
                    }
            });
	</script>

<?php
	$mulai = $_GET['mulai'];
	$sampai = $_GET['sampai'];
	$id_transaksi = $_GET['id_transaksi'];
	$id_pelanggan = $_GET['pelanggan'];
	if ($id_transaksi=="" && $mulai!="") {
?>
	<table>
		<thead>
			<tr>
				<th>Id Transaksi</th>
				<th>Tanggal</th>
				<th>Jatuh Tempo</th>
				<th>Nama Pelanggan</th>
				<th>Tunggakan</th>
				<th class="display">Pengaturan</th>
			</tr>
		</thead>
		<tbody>
<?php
		if ($sampai=="") {
			$sampai = date("Y")."-12-31";
		}
		$query = "SELECT a.*,b.nama_pelanggan FROM transaksi a INNER JOIN pelanggan b ON a.id_pelanggan=b.id_pelanggan WHERE tunggakan>0 AND (tanggal_transaksi BETWEEN '$mulai' AND '$sampai')";
		if ($id_pelanggan!="semua") {
			$query.=" AND a.id_pelanggan=$id_pelanggan;";
		}
		else{
			$query.=";";
		}
		$result = mysql_query($query);
		while ($data=mysql_fetch_assoc($result)) {
			echo "
					<tr>
						<td>".$data['id_transaksi']."</td>
						<td>".strtok($data['tanggal_transaksi'],' ')."</td>
						<td>".$data['jatuh_tempo']."</td>
						<td>".$data['nama_pelanggan']."</td>
						<td align=\"right\">".number_format($data['tunggakan'],0,0,'.')."</td>
						<td class=\"display\">
							<a href=\"?halaman=invoice&id_transaksi=".$data['id_transaksi']."&laporan=detail_piutang\">Perbarui</a>
						</td>
					</tr>";
		}
?>
		</tbody>
	</table>
<?php
	}
?>