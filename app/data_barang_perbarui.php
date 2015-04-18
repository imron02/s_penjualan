
<?php 
	if($_POST)
	{
		
		if( db::query()->cekId(HALAMAN_AKTIF,array('kode_barang'=>$_POST['kode_barang'],'kode_barang!'=>$_GET['perbarui_id'])) >=  1)
		{
			echo "<strong class='error'>Kode barang '{$_POST['kode_barang']}' sudah tersedia.</strong>";
		}
		
	}
	
	$o = db::query()->perbarui(HALAMAN_AKTIF,$_POST,array('kode_'.HALAMAN_AKTIF=>$_GET['perbarui_id']));
?>

<form action="" class="formulir-umum" method="post">
	<fieldset>
		<legend>Tambah Barang</legend>
			<label>Kode Barang :</label>
			<input type="text" name="kode_barang" value="<?php echo $o->kode_barang;?>"/>
			<label>Tipe Barang : </label>
			<select name="id_tipe_barang">
			<?php 
			$Q = mysql_query("SELECT tipe_barang.*,IF(id_tipe_barang={$o->id_tipe_barang},'selected','') AS terpilih  FROM tipe_barang");
			while ($n = mysql_fetch_object($Q)):
				echo '<option value="'.$n->id_tipe_barang.'" '.$n->terpilih.'>'.$n->nama_tipe_barang.'</option>';
			endwhile;
			?>
			</select>
			<label>Nama Barang : </label>
			<input type="text" name="nama_barang"  value="<?php echo $o->nama_barang;?>"/>
			<label>Harga : </label>
			<input type="text" class="nomor" name="harga" value="<?php echo $o->harga?>"/>
			<hr />
			<button class="tombol">Simpan</button>
			<a href="?halaman=barang" class="tombol">Kembali</a>
			<hr />
	</fieldset>
</form>
