<?php 
	if($_POST)
	{
		if( db::query()->cekId(HALAMAN_AKTIF,array('kode_pengguna'=>$_POST['kode_pengguna'],'kode_pengguna!'=>$_GET['perbarui_id'])) >=  1)
		{
			echo "<strong class='error'>Kode pengguna '{$_POST['kode_pengguna']}' sudah tersedia.</strong>";
		}
	}
	
	$o = db::query()->perbarui(HALAMAN_AKTIF,$_POST,array('kode_'.HALAMAN_AKTIF=>$_GET['perbarui_id']));
?>

<form action="" class="formulir-umum" method="post">
	<fieldset>
		<legend>Tambah Pengguna</legend>
		<label>Kode Pengguna : </label>
		<input type="text" name="kode_pengguna" value="<?php echo $o->kode_pengguna;?>" />
		<label>Nama Pengguna :</label>
		<input type="text" name="nama_pengguna" value="<?php echo $o->nama_pengguna;?>" />
		<label>Kata Kunci : </label>
		<input type="password" name="kata_kunci" value="<?php echo $o->kata_kunci;?>" />
		<hr />
		<button class="tombol">Simpan</button>
		<a href="?halaman=pengguna" class="tombol">Kembali</a>
		<hr />
	</fieldset>
</form>
