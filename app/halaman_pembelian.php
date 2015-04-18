<?php 
if($_POST)
{
	db::query()->simpan('transaksi',array(
		'id_supplier'=>$_POST['id_supplier'],
		'tipe_transaksi'=>'pembelian'
	),
	FALSE);
	$id = mysql_insert_id($koneksi);
	foreach($_POST['kode_barang'] as $no => $kode_barang)
	{
		if((int)$_POST['banyaknya'][$no] > 0)
		{
			db::query()->simpan('transaksi_rinci',
					array(
							'kode_barang'=>$kode_barang,
							'banyaknya'=>mysql_real_escape_string($_POST['banyaknya'][$no]),
							'harga_saatini'=>$_POST['harga_saatini'][$no],
							'jam'=>date("H:i:s",time()),
							'id_transaksi'=>$id
					)
					,FALSE);
			mysql_query("UPDATE barang SET stok = stok + ".mysql_real_escape_string($_POST['banyaknya'][$no])." WHERE kode_barang = '{$kode_barang}'");
		}
		
	}
	
	header("location:?halaman=laporan&jenis_laporan=pembelian");
	exit();
}?>
<form action="" method="post" class="formulir-umum" enctype="multipart/form-data">
	<fieldset>
		<legend>Tambah Pembelian</legend>
		<label>Supplier : </label>
		<select name="id_supplier" >
			<?php 
			$Q = mysql_query("SELECT * FROM supplier");
			while ($c = mysql_fetch_object($Q)):
				echo '<option value="'.$c->id_supplier.'">'.$c->nama_supplier.'</option>';
			endwhile;
			?>
		</select>
		<hr />
		<label>Pilih barang :</label>
		
		<table>
			<thead>
				<tr>
					<th>Kode</th>
					<th>Nama</th>
					<th>Harga</th>
					<th>stok</th>
					<th>Tipe</th>
					<th>Jumlah Pembelian</th>
				</tr>
			</thead>
			<tbody>
				<?php  
				$Q = mysql_query("SELECT barang.*,tipe_barang.nama_tipe_barang FROM barang LEFT JOIN tipe_barang ON tipe_barang.id_tipe_barang = barang.id_tipe_barang"); 
				$n = 0;
				while($o = mysql_fetch_object($Q)):
				
					echo "
					<tr>
						<td>{$o->kode_barang}</td>
						<td>{$o->nama_barang}</td>
						<td align='right'>".number_format($o->harga)."</td>
						<td align='right'>".number_format($o->stok)."</td>
						<td>{$o->nama_tipe_barang}</td>
						<td>
							<input type='hidden' name='kode_barang[{$n}]' value='{$o->kode_barang}' />
							<input type='hidden' name='harga_saatini[{$n}]' value='{$o->harga}' />
							<input type='text' class='nomor' name='banyaknya[{$n}]' value='0' />
						</td>
					</tr>";
					$n++;
				endwhile;
				?>
			</tbody>
		</table>
		
		<hr />
		
		<button type="submit" class="tombol">Simpan</button>
		<a href="?halaman=belanja" class="tombol">Kembali</a>
		<hr />
	</fieldset>
</form>