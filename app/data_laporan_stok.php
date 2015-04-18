<h1 class="sub-judul">Laporan Stok Barang</h1>

<p class="display">
	<button class="tombol" onclick="window.print()">Cetak</button>
</p>

<table>
	<thead>
		<tr>
			<th>Kode</th>
			<th>Nama</th>
			<th>Tipe</th>
			<th>Jumlah Persediaan / stok</th>
		</tr>
	</thead>
	<tbody>
		<?php  
		$Q = mysql_query("SELECT barang.*,tipe_barang.nama_tipe_barang FROM barang LEFT JOIN tipe_barang ON tipe_barang.id_tipe_barang = barang.id_tipe_barang"); 
		
		while($o = mysql_fetch_object($Q)):
		
			echo "
			<tr>
				<td>{$o->kode_barang}</td>
				<td>{$o->nama_barang}</td>
				<td>{$o->nama_tipe_barang}</td>
				<td>".number_format($o->stok,0,'.',',')."</td>
			</tr>";
		
		endwhile;
		?>
	</tbody>
</table>