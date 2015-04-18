<h1 class="sub-judul">Daftar Pengguna</h1>


<?php if(isset($_GET['tambah']) && $_GET['tambah'] == 1 ):?>

	<?php require_once DIR_APP . 'data_pengguna_tambah.php';?>

<?php elseif(isset($_GET['perbarui_id']) && $_GET['perbarui_id'] != '' ):?>

	<?php require_once DIR_APP . 'data_pengguna_perbarui.php';?>
	
<?php elseif(isset($_GET['hapus_id']) && $_GET['hapus_id'] != '' ):?> 

	<?php if($_POST){
		$id = mysql_real_escape_string($_POST['hapus_id']);
		mysql_query("DELETE FROM ".HALAMAN_AKTIF." WHERE kode_".HALAMAN_AKTIF." = '{$id}'");
		
		header('location:?halaman='.HALAMAN_AKTIF);
		exit();
	}?>
	<form action="" method="post">
		<h3>Hapus <?php echo HALAMAN_AKTIF;?>?</h3>
		<input type="hidden" value="<?php echo $_GET['hapus_id'];?>" name="hapus_id" />
		<button type="submit" class="tombol">Hapus</button>
		<a href="?halaman=<?php echo HALAMAN_AKTIF;?>" class="tombol">Batal</a>
	</form>
	
<?php else:?>
<a href="?halaman=pengguna&tambah=1" class="tombol kanan">Tambah Pengguna</a>
<table>
	<thead>
		<tr>
			<th>Kode Pengguna</th>
			<th>Nama</th>
			<th>Kata Kunci</th>
			<th>Pengaturan</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	$Q = mysql_query("SELECT * FROM pengguna");
	while($o = mysql_fetch_object($Q)):
	?>
		<tr>
			<td><?php echo $o->kode_pengguna;?></td>
			<td><?php echo $o->nama_pengguna;?></td>
			<td><?php echo preg_replace("/[^*]/",'*',$o->kata_kunci);?></td>
			<td>
				<a href="?halaman=pengguna&perbarui_id=<?php echo $o->kode_pengguna;?>">Perbarui</a> ,   
				<a href="?halaman=pengguna&hapus_id=<?php echo $o->kode_pengguna;?>">Hapus</a>
			</td>
		</tr>
	<?php 
	endwhile;
	?>
	</tbody>
</table>
<?php endif;?>