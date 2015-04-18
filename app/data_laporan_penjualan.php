<h1 class="sub-judul">Laporan <?php echo ucfirst($_GET['jenis_laporan'])?> Barang</h1>

<?php 
	$id_transaksi = isset($_GET['id_transaksi']) && (int) $_GET['id_transaksi'] > 0 ? $_GET['id_transaksi'] : FALSE;
	$kolomDetail = ($_GET['jenis_laporan'] == 'penjualan')?'pelanggan':'supplier';
	if(!$id_transaksi):
?>
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
	if(isset($_GET['mulai'])):
	
		
	
	?>
	<table>
		<thead>
			<tr>
				<th>Id Transaksi</th>
				<th>Tanggal</th>
				<th><?php echo "Nama {$kolomDetail}";?></th>
				<th>Total Belanja</th>
				<th class="display">Pengaturan</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			$where = "AND t.tanggal_transaksi >= '{$_GET['mulai']}' AND t.tanggal_transaksi <= '{$_GET['sampai']}' ";
			if($_GET['pelanggan'] != 'semua')$where.= " AND t.id_pelanggan = '{$_GET['pelanggan']}'";
			$dataTransaksi = mysql_query("
					SELECT 
						* 
					FROM 
						transaksi t
					LEFT JOIN
						supplier s ON s.id_supplier=t.id_supplier
					LEFT JOIN
						pelanggan p ON p.id_pelanggan=t.id_pelanggan 
					WHERE 
						t.tipe_transaksi = '{$_GET['jenis_laporan']}'
						{$where} 
					ORDER BY
						t.tanggal_transaksi DESC
					")or die(mysql_error());
			$nama = "nama_{$kolomDetail}";
			$subtotal = 0;
			$total = 0;
			while($o = mysql_fetch_object($dataTransaksi)):
			
				$t = (object)mysql_fetch_assoc(mysql_query("SELECT SUM(banyaknya*harga_saatini) harga FROM transaksi_rinci WHERE id_transaksi = '{$o->id_transaksi}'"));
				$subtotal = $t->harga;
				$total+=$o->grand_total;
				echo "
						<tr>
							<td>{$o->id_transaksi}</td>
							<td>".strtok($o->tanggal_transaksi,' ')."</td>
							<td>{$o->{$nama}}</td>
							<td align=\"right\">".number_format($o->grand_total,0,0,'.')."</td>
							<td class=\"display\">
								<a href=\"?halaman=invoice&id_transaksi={$o->id_transaksi}&laporan=detail_penjualan\">Rinci</a> | 
								<a href=\"?halaman=laporan_hapus&jenis_laporan={$_GET['jenis_laporan']}&id_transaksi={$o->id_transaksi}&mulai={$_GET['mulai']}&sampai={$_GET['sampai']}&pelanggan={$_GET['pelanggan']}\">Hapus</a>
							</td>
						</tr>";
			
			endwhile;?>
			<tr>
				<td align="right" colspan="3">Total</td>
				<td align="right"><?php echo number_format($total,0,0,'.');?></td>
				<td class="display"></td>
			</tr>
		</tbody>
	</table>
	<hr/>
	<a href="?halaman=laporan&jenis_laporan=penjualan" class="tombol kanan display" style="margin-left:20px;">Kembali</a><a href="#" onclick="window.print()" class="tombol kanan display">Cetak</a>
	<?php
	// pencarian
		endif;
	?>
<?php else:?>
<p class="display">
	<a href="?halaman=laporan&jenis_laporan=<?php echo $_GET['jenis_laporan'];?>&mulai=<?php echo $_GET['mulai']?>&sampai=<?php echo $_GET['sampai']?>&pelanggan=<?php echo $_GET['pelanggan']?>" class="tombol">&laquo; Kembali ke laporan <?php echo $_GET['jenis_laporan'];?></a>
	<button class="tombol" onclick="window.print()">Cetak</button>
</p>
<h2>ID TRANSAKSI [<?php echo $_GET['id_transaksi'];?>]</h2>
<table>
	<thead>
		<tr>
			<th>Kode</th>
			<th>Nama</th>
			<th>Tipe Barang</th>
			<th>Jumlah Barang</th>
			<th>Harga Barang (Rp.)</th>
			<th>Subtotal (Rp.)</th>
		</tr>
	</thead>
	<tbody>
		<?php  
		$Q = mysql_query("
				SELECT
					*
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
		while($o = mysql_fetch_object($Q)):
		
		$total = $o->banyaknya*$o->harga_saatini;
		$keseluruhan+=$total;
		echo "
			<tr>
				<td>{$o->kode_barang}</td>
				<td>{$o->nama_barang}</td>
				<td>{$o->nama_tipe_barang}</td>
				<td>{$o->banyaknya}</td>
				<td align='right'>".number_format($o->harga_saatini,0,',','.')."</td>
				<td align='right'>".number_format($total,0,',','.')."</td>
			</tr>";
		
		endwhile;
		?>
	</tbody>
	<tfoot>
		<tr>
			<th align="right" colspan="5">Total (Rp.)</th>
			<th align="right"><?php echo number_format($keseluruhan,0,',','.');?></th>
		</tr>
	</tfoot>
</table>
<?php endif;?>
