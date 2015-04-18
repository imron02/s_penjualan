<?php if($_POST)db::query()->simpan(HALAMAN_AKTIF);?>
<form action="" class="formulir-umum" method="post">
	<fieldset>
		<legend>Tambah Karyawan</legend>
		<label>Nama : </label>
		<input type="text" name="nama_karyawan" />
		<label>Jabatan :</label>
		<input type="text" name="jabatan"/>
		<label>KTP : </label>
		<input type="text" name="ktp"/>
		<label>Alamat :</label>
		<textarea name="alamat" rows="10"></textarea>
		<label>Telepon : </label>
		<input type="text" name="telepon"/>
		<hr />
		<button class="tombol">Simpan</button>
		<a href="?halaman=karyawan" class="tombol">Kembali</a>
		<hr />
	</fieldset>
</form>
