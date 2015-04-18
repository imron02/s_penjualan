<h1 class="sub-judul">Daftar Karyawan</h1>

<?php if(isset($_GET['tambah']) && $_GET['tambah'] == 1 ):?>

	<?php require_once DIR_APP . 'data_karyawan_tambah.php';?>
	
<?php elseif(isset($_GET['perbarui_id']) && $_GET['perbarui_id'] >= 1 ):?>

	<?php require_once DIR_APP . 'data_karyawan_perbarui.php';?>
	
<?php elseif(isset($_GET['hapus_id']) && $_GET['hapus_id'] >= 1 ):?> 

	<?php if($_POST){
		$id = mysql_real_escape_string($_POST['hapus_id']);
		mysql_query("DELETE FROM ".HALAMAN_AKTIF." WHERE id_".HALAMAN_AKTIF." = '{$id}'");
		
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
<a href="?halaman=karyawan&tambah=1" class="tombol kanan">Tambah Karyawan</a>
<table>
	<thead>
		<tr>
			<th>Nama karyawan</th>
			<th>Jabatan</th>
			<th>Ktp</th>
			<th>Alamat</th>
			<th>Telepon</th>
			<th>Pengaturan</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	$Q = mysql_query("SELECT * FROM karyawan");
	while($o = mysql_fetch_object($Q)):
	?>
		<tr>
			<td><?php echo $o->nama_karyawan;?></td>
			<td><?php echo $o->jabatan;?></td>
			<td><?php echo $o->ktp;?></td>
			<td><?php echo $o->alamat;?></td>
			<td><?php echo $o->telepon;?></td>
			<td>  
				<a href="?halaman=karyawan&perbarui_id=<?php echo $o->id_karyawan;?>">Perbarui</a> ,   
				<a href="?halaman=karyawan&hapus_id=<?php echo $o->id_karyawan;?>">Hapus</a>
			</td>
		</tr>
	<?php 
	endwhile;
	?>
	</tbody>
</table>
<?php endif;?>