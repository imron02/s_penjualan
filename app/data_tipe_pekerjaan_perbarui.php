<?php $o = db::query()->perbarui(HALAMAN_AKTIF,$_POST,array('id_'.HALAMAN_AKTIF=>$_GET['perbarui_id']));?>
<form action="" class="formulir-umum" method="post">
	<fieldset>
		<legend>Tambah Tipe Pekerjaan</legend>
		<label>Nama Tipe Pekerjaan : </label>
		<input type="text" name="nama_tipe_pekerjaan" value="<?php echo $o->nama_tipe_pekerjaan;?>"/>
		<label>Harga Jasa :</label>
		<input type="text" class="nomor" name="harga_jasa_pekerjaan" value="<?php echo $o->harga_jasa_pekerjaan;?>" />
		<hr />
		<button class="tombol">Simpan</button>
		<a href="?halaman=tipe_pekerjaan" class="tombol">Kembali</a>
		<hr />
	</fieldset>
</form>
