<?php $o = db::query()->perbarui(HALAMAN_AKTIF,$_POST,array('id_'.HALAMAN_AKTIF=>$_GET['perbarui_id']));?>
<form action="" class="formulir-umum" method="post">
	<fieldset>
		<legend>Tambah Pelanggan</legend>
		<label>Nama : </label>
		<input type="text" name="nama_pelanggan" value="<?php echo $o->nama_pelanggan;?>" />
		<label>Alamat :</label>
		<textarea name="alamat" rows="10"><?php echo $o->alamat;?></textarea>
		<label>Telepon : </label>
		<input type="text" name="telepon" value="<?php echo $o->telepon;?>"/>
         <hr />
		<button class="tombol">Simpan</button>
		<a href="?halaman=pelanggan" class="tombol">Kembali</a>
		<hr />
	</fieldset>
</form>
