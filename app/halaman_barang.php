<h1 class="sub-judul">Daftar Barang</h1>
<?php if(isset($_GET['tambah']) && $_GET['tambah'] == 1 ):?>

	<?php require_once DIR_APP . 'data_barang_tambah.php';?>

<?php elseif(isset($_GET['perbarui_id']) && $_GET['perbarui_id'] != '' ):?>

	<?php require_once DIR_APP . 'data_barang_perbarui.php';?>
	
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
<a href="?halaman=barang&tambah=1" class="tombol kanan">Tambah Barang</a>
<table>
	<thead>
		<tr>
			<th>Kode</th>
			<th>Nama</th>
			<th>Harga</th>
			<th>Tipe</th>
			<th>Pengaturan</th>
		</tr>
	</thead>
	<tbody>
		<?php  
		$Q = mysql_query("SELECT barang.*,tipe_barang.nama_tipe_barang FROM barang LEFT JOIN tipe_barang ON tipe_barang.id_tipe_barang = barang.id_tipe_barang"); 
		
		while($o = mysql_fetch_object($Q)):
		
			echo "
			<tr>
				<td>{$o->kode_barang}</td>
				<td>{$o->nama_barang}</td>
				<td>".number_format($o->harga,0,'.',',')."</td>
				<td>{$o->nama_tipe_barang}</td>
				<td>
					<a href='?halaman=barang&perbarui_id={$o->kode_barang}'>Perbarui</a> ,
					<a href='?halaman=barang&hapus_id={$o->kode_barang}'>Hapus</a>
				</td>
			</tr>";
		
		endwhile;
		?>
	</tbody>
</table>
<?php endif;?>