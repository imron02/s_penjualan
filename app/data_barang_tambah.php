
<?php 
	if($_POST)
	{
		if( db::query()->cekId('barang',array('kode_barang'=>$_POST['kode_barang'])) < 1)
		{
			db::query()->simpan('barang');
		}
		else
		{
			echo "<strong class='error'>Kode barang '{$_POST['kode_barang']}' sudah tersedia.</strong>";
		}
	}
?>

<form action="" class="formulir-umum" method="post">
	<fieldset>
		<legend>Tambah Barang</legend>
			<label>Kode Barang :</label>
			<input type="text" name="kode_barang" />
			<label>Tipe Barang : </label>
			<select name="id_tipe_barang">
			<?php 
			$Q = mysql_query("SELECT * FROM tipe_barang");
			while ($o = mysql_fetch_object($Q)):
				echo '<option value="'.$o->id_tipe_barang.'">'.$o->nama_tipe_barang.'</option>';
			endwhile;
			?>
			</select>
			<label>Nama Barang : </label>
			<input type="text" name="nama_barang" />
			<label>Harga : </label>
			<input type="text" class="nomor" name="harga"/>
			<label>Persediaan / Stok Awal : </label>
			<input type="text" class="nomor" name="stok" />
			<hr />
			<button class="tombol">Simpan</button>
			<a href="?halaman=barang" class="tombol">Kembali</a>
			<hr />
	</fieldset>
</form>
