<?php $o = db::query()->perbarui(HALAMAN_AKTIF,$_POST,array('id_karyawan'=>$_GET['perbarui_id']));?>
<form action="" class="formulir-umum" method="post">
	<fieldset>
		<legend>Tambah Karyawan</legend>
		<label>Nama : </label>
		<input type="text" name="nama_karyawan" value="<?php echo $o->nama_karyawan;?>" />
		<label>Jabatan :</label>
		<input type="text" name="jabatan" value="<?php echo $o->jabatan;?>"/>
		<label>KTP : </label>
		<input type="text" name="ktp" value="<?php echo $o->ktp;?>"/>
		<label>Alamat :</label>
		<textarea name="alamat" rows="10"><?php echo $o->alamat;?></textarea>
		<label>Telepon : </label>
		<input type="text" name="telepon" value="<?php echo $o->telepon;?>"/>
		<hr />
		<button class="tombol">Simpan</button>
		<a href="?halaman=karyawan" class="tombol">Kembali</a>
		<hr />
	</fieldset>
</form>
