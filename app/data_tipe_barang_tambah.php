<?php 
	if($_POST)db::query()->simpan(HALAMAN_AKTIF);
?>

<form action="" class="formulir-umum" method="post">
	<fieldset>
		<legend>Tambah Tipe Barang</legend>
		<label>Nama Tipe Barang : </label>
		<input type="text" name="nama_tipe_barang" />
		<hr />
		<button class="tombol">Simpan</button>
		<a href="?halaman=tipe_barang" class="tombol">Kembali</a>
		<hr />
	</fieldset>
</form>
