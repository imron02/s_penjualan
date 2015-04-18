<?php 

$id_transaksi = $_GET['id_transaksi'];
if($_POST)
{
	$O = mysql_query("SELECT * FROM transaksi_rinci WHERE id_transaksi = '{$id_transaksi}'");
	
	while($S = mysql_fetch_object($O))
	{
		mysql_query("UPDATE barang SET stok = stok - {$S->banyaknya} WHERE kode_barang = '{$S->kode_barang}'");
	}
	mysql_query("DELETE FROM transaksi WHERE id_transaksi = '{$id_transaksi}'");
	mysql_query("DELETE FROM transaksi_rinci WHERE id_transaksi = '{$id_transaksi}'");
	
	db::query()->simpan('transaksi',array(
		'id_transaksi'=>$id_transaksi,
		'id_supplier'=>$_POST['id_supplier'],
		'tipe_transaksi'=>'pembelian'
	),
	FALSE);
	foreach($_POST['kode_barang'] as $no => $kode_barang)
	{
		if((int)$_POST['banyaknya'][$no] > 0)
		{
			db::query()->simpan('transaksi_rinci',
					array(
							'kode_barang'=>$kode_barang,
							'banyaknya'=>mysql_real_escape_string($_POST['banyaknya'][$no]),
							'harga_saatini'=>$_POST['harga_saatini'][$no],
							'id_transaksi'=>$id_transaksi
					)
					,FALSE);
			mysql_query("UPDATE barang SET stok = stok + ".mysql_real_escape_string($_POST['banyaknya'][$no])." WHERE kode_barang = '{$kode_barang}'");
		}
		
	}
	
	header("location:?halaman=laporan&jenis_laporan=pembelian");
	exit();
}

$SQL = mysql_query("SELECT * FROM transaksi WHERE id_transaksi = '{$id_transaksi}' LIMIT 1");
$data = (object) mysql_fetch_assoc($SQL);
?>
<form action="" method="post" class="formulir-umum" enctype="multipart/form-data">
	<fieldset>
		<legend>Perbarui Pembelian</legend>
		<label>Supplier : </label>
		<select name="id_supplier" >
			<?php 
			$Q = mysql_query("SELECT supplier.*,IF(supplier.id_supplier={$data->id_supplier},'selected','') AS supplier_selected FROM supplier");
			while ($c = mysql_fetch_object($Q)):
				echo '<option value="'.$c->id_supplier.'" '.$c->supplier_selected.'>'.$c->nama_supplier.'</option>';
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
					<th>Jumlah Persediaan / stok</th>
					<th>Jumlah Belanja</th>
					<th>Tipe</th>
				</tr>
			</thead>
			<tbody>
				<?php  
				$Q = mysql_query("
				SELECT barang.*,tipe_barang.nama_tipe_barang,transaksi_rinci.banyaknya
				FROM barang 
				LEFT JOIN tipe_barang ON tipe_barang.id_tipe_barang = barang.id_tipe_barang
				LEFT JOIN transaksi_rinci ON (barang.kode_barang = transaksi_rinci.kode_barang AND transaksi_rinci.id_transaksi = '{$id_transaksi}')
				")or die(mysql_error()); 
				$n = 0;
				while($o = mysql_fetch_object($Q)):
				
					echo "
					<tr>
						<td>{$o->kode_barang}</td>
						<td>{$o->nama_barang}</td>
						<td>{$o->harga}</td>
						<td>{$o->stok}</td>
						<td>
							<input type='hidden' name='kode_barang[{$n}]' value='{$o->kode_barang}' />
							<input type='hidden' name='harga_saatini[{$n}]' value='{$o->harga}' />
							<input type='text' name='banyaknya[{$n}]' value='{$o->banyaknya}' />
						</td>
						<td>{$o->nama_tipe_barang}</td>
					</tr>";
					$n++;
				endwhile;
				?>
			</tbody>
		</table>
		
		<hr />
		
		<button type="submit" class="tombol">Simpan</button>
		<a href="?halaman=transaksi" class="tombol">Kembali</a>
		<hr />
	</fieldset>
</form>