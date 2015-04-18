<h1 class="sub-judul display">Surat Jalan</h1>

<?php 
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
								<a href=\"?halaman=surat_jalan&id_transaksi={$o->id_transaksi}\">Rincian Surat Jalan</a>
							</td>
						</tr>";
			
			endwhile;?>
		</tbody>
	</table>
	
<?php else:?>
<p class="display">
	<a href="?halaman=surat_jalan" class="tombol">&laquo; Kembali ke daftar surat jalan</a>
	<button class="tombol" onclick="window.open('cetakSuratJalan.php?id_transaksi=<?php echo($_GET['id_transaksi']); ?>')">Cetak</button>
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
?>
<table>
	<thead>
		<tr>
			<th colspan="2">Detail Surat Jalan</th>
		</tr>
		<tr>
			<th align="right">Tanggal :</th>
			<th align="left"><?php echo strtok($D->tanggal_transaksi,' ');?></th>
		</tr>
		<tr>
			<th align="right" width="200">Kepada :</th>
			<th align="left"><?php echo $D->nama_pelanggan;?></th>
		</tr>
		<tr>
			<th align="right" width="200">Alamat :</th>
			<th align="left"><?php echo $D->nama_pelanggan;?></th>
		</tr>
		<tr>
			<th align="right" width="200">Telepon :</th>
			<th align="left"><?php echo $D->telepon;?></th>
		</tr>
	</thead>
</table>
<hr />
<table>
	<thead>
		<tr>
			<th>Kode</th>
			<th>Nama</th>
			<th>Tipe Barang</th>
			<th>Jumlah Barang</th>
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
			</tr>";
		
		endwhile;
		?>
	</tbody>
	<tfoot>
		<tr>
			<th align="right" style="border-right:0;" colspan="3"></th>
			<th align="center"><br />
			Penerima
			<br />
			<br />
			<br />
			<br />
			<br />
			( <?php echo $D->nama_pelanggan;?> )
			<br />
			<br />
			<br />
			</th>
		</tr>
	</tfoot>
</table>
<?php endif;?>