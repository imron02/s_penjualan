<h1 class="sub-judul">Daftar Tipe Barang</h1>
<?php if(isset($_GET['tambah']) && $_GET['tambah'] == 1 ):?>

	<?php require_once DIR_APP . 'data_tipe_barang_tambah.php';?>
	
<?php elseif(isset($_GET['perbarui_id']) && $_GET['perbarui_id'] >= 1 ):?>

	<?php require_once DIR_APP . 'data_tipe_barang_perbarui.php';?>
	
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
<a href="?halaman=tipe_barang&tambah=1" class="tombol kanan">Tambah Tipe Barang</a>
<table>
	<thead>
		<tr>
			<th>Satuan</th>
			<th>Pengaturan</th>
		</tr>
	</thead>
	<tbody>
		<?php  
		$Q = mysql_query("SELECT * FROM tipe_barang"); 
		
		while($o = mysql_fetch_object($Q)):
		
			echo "
			<tr>
				<td>{$o->nama_tipe_barang}</td>
				<td>
					<a href='?halaman=tipe_barang&perbarui_id={$o->id_tipe_barang}'>Perbarui</a> ,
					<a href='?halaman=tipe_barang&hapus_id={$o->id_tipe_barang}'>Hapus</a> 
				</td>
			</tr>";
		
		endwhile;
		?>
	</tbody>
</table>
<?php endif;?>