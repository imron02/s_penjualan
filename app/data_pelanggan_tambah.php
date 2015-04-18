<?php if($_POST)db::query()->simpan(HALAMAN_AKTIF);?>
<form action="" class="formulir-umum" method="post">
	<fieldset>
		<legend>Tambah Pelanggan</legend>
		<label>Nama : </label>
		<input type="text" name="nama_pelanggan" />
		<label>Alamat :</label>
		<textarea name="alamat" rows="10"></textarea>
		<label>Telepon : </label>
		<input type="text" name="telepon"/>
		<hr />
		<button class="tombol">Simpan</button>
		<a href="?halaman=pelanggan" class="tombol">Kembali</a>
		<hr />
	</fieldset>
</form>
