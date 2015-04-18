<?php $o = db::query()->perbarui(HALAMAN_AKTIF,$_POST,array('id_'.HALAMAN_AKTIF=>$_GET['perbarui_id']));?>
<form action="" class="formulir-umum" method="post">
	<fieldset>
		<legend>Perbarui Pemasuk / Supplier</legend>
		<label>Nama : </label>
		<input type="text" name="nama_supplier" value="<?php echo $o->nama_supplier;?>" />
		<label>Perusahaan :</label>
		<input type="text" name="nama_perusahaan" value="<?php echo $o->nama_perusahaan;?>" />
		<label>Telepon : </label>
		<input type="text"  name="telepon" value="<?php echo $o->telepon;?>" />
		<label>Alamat : </label>
		<textarea name="alamat" rows="10"><?php echo $o->alamat;?></textarea>
		<hr />
		<button class="tombol">Simpan</button>
		<a href="?halaman=supplier" class="tombol">Kembali</a>
		<hr />
	</fieldset>
</form>
