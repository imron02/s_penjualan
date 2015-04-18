<?php if($_POST)db::query()->simpan(HALAMAN_AKTIF);?>
<form action="" class="formulir-umum" method="post">
	<fieldset>
		<legend>Tambah Pemasuk / Supplier</legend>
		<label>Nama : </label>
		<input type="text" name="nama_supplier" />
		<label>Perusahaan :</label>
		<input type="text" name="nama_perusahaan" />
		<label>Telepon : </label>
		<input type="text"  name="telepon"/>
		<label>Alamat : </label>
		<textarea name="alamat" rows="10"></textarea>
		<hr />
		<button class="tombol">Simpan</button>
		<a href="?halaman=supplier" class="tombol">Kembali</a>
		<hr />
	</fieldset>
</form>
