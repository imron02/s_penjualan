<h1 class="sub-judul">Laporan <?php echo ucfirst($_GET['jenis_laporan'])?> Barang</h1>

<?php 
	$id_transaksi = isset($_GET['id_transaksi']) && (int) $_GET['id_transaksi'] > 0 ? $_GET['id_transaksi'] : FALSE;
	$kolomDetail = ($_GET['jenis_laporan'] == 'penjualan')?'pelanggan':'supplier';
	if(!$id_transaksi):
?>

	
	<table>
		<thead>
			<tr>
				<th>Id Transaksi</th>
				<th>Tanggal</th>
				<th><?php echo "Nama {$kolomDetail}";?></th>
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
						supplier s ON s.id_supplier=t.id_supplier
					LEFT JOIN
						pelanggan p ON p.id_pelanggan=t.id_pelanggan 
					WHERE 
						t.tipe_transaksi = '{$_GET['jenis_laporan']}'
					ORDER BY
						t.tanggal_transaksi DESC
					");
			$nama = "nama_{$kolomDetail}";
			while($o = mysql_fetch_object($dataTransaksi)):
			
				echo "
						<tr>
							<td>{$o->id_transaksi}</td>
							<td>".strtok($o->tanggal_transaksi,' ')."</td>
							<td>{$o->{$nama}}</td>
							<td>
								<a href=\"?halaman=laporan&jenis_laporan={$_GET['jenis_laporan']}&id_transaksi={$o->id_transaksi}\">Rinci</a> | 
								<a href=\"?halaman=laporan_hapus&jenis_laporan={$_GET['jenis_laporan']}&id_transaksi={$o->id_transaksi}\">Hapus</a> | 
								<a href=\"?halaman=transaksi_pembelian_perbarui&jenis_laporan={$_GET['jenis_laporan']}&id_transaksi={$o->id_transaksi}\">Perbarui</a>
							</td>
						</tr>";
			
			endwhile;?>
		</tbody>
	</table>
	
<?php else:?>
<p class="display">
	<a href="?halaman=laporan&jenis_laporan=<?php echo $_GET['jenis_laporan'];?>" class="tombol">&laquo; Kembali ke laporan <?php echo $_GET['jenis_laporan'];?></a>
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
			<th>Harga Barang</th>
			<th>Subtotal</th>
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
				<td>".number_format($o->banyaknya,0,'.',',')."</td>
				<td>".number_format($o->harga_saatini,0,'.',',')."</td>
				<td align='right'><label class='kiri'>Rp.</label>".number_format($total,0,'.',',')."</td>
			</tr>";
		
		endwhile;
		?>
	</tbody>
	<tfoot>
		<tr>
			<th align="right" colspan="5">Total</th>
			<th align="right"><label class='kiri'>Rp.</label><?php echo number_format($keseluruhan,0,'.',',');?></th>
		</tr>
	</tfoot>
</table>
<?php endif;?>