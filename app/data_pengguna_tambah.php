<?php 
	if($_POST)
	{
		if( db::query()->cekId('pengguna',array('kode_pengguna'=>$_POST['kode_pengguna'])) < 1)
		{
			db::query()->simpan('pengguna');
		}
		else
		{
			echo "<strong class='error'>Kode pengguna '{$_POST['kode_pengguna']}' sudah tersedia.</strong>";
		}
	}
?>

<form action="" class="formulir-umum" method="post">
	<fieldset>
		<legend>Tambah Pengguna</legend>
		<label>Kode Pengguna : </label>
		<input type="text" name="kode_pengguna" />
		<label>Nama Pengguna :</label>
		<input type="text" name="nama_pengguna" />
		<label>Kata Kunci : </label>
		<input type="password" name="kata_kunci" />
		<hr />
		<button class="tombol">Simpan</button>
		<a href="?halaman=pengguna" class="tombol">Kembali</a>
		<hr />
	</fieldset>
</form>
