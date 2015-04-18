<?php 
$error = '';
if($_POST)
{
	$id_pelanggan = mysql_real_escape_string($_POST['id_pelanggan']);
	$sql = mysql_query("SELECT * FROM pelanggan WHERE id_pelanggan = '{$id_pelanggan}' LIMIT 1");
	if(mysql_num_rows($sql) > 0)
	{
		header("location:?halaman=konsumen&id_pelanggan={$id_pelanggan}");
	}
	else
	{
		$error = '<h4 style="color:#ff0000">Id pelanggan tidak ditemukan mohon hubungi admin.</h4>';	
	}
}
?>

<form action="" class="formulir-utama display" method="post">
	<fieldset>
		<legend>Data Pelanggan</legend>
		<?php echo $error;?>
		<label>Masukkan ID Pelanggan : </label>
		<input type="text" name="id_pelanggan" />
		<hr />
		<button class="tombol">Cari</button>
		<hr />
	</fieldset>
</form>



<?php if(isset($_GET['id_pelanggan']) && $_GET['id_pelanggan'] > 0):?>



<?php if(isset($_GET['lihat_id']) && $_GET['lihat_id'] > 0):?>

	<?php 
	$id_serv = mysql_real_escape_string($_GET['lihat_id']);
	
	$SQL = mysql_query("SELECT * FROM servis WHERE id_servis = '{$id_serv}' LIMIT 1");
	$dataRinci = (object) mysql_fetch_assoc($SQL);
	
?>

<form action="" class="formulir-umum" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend><strong>Detail Servis Pelanggan : <a href="?halaman=konsumen&id_pelanggan=<?php echo $_GET['id_pelanggan'];?>" class="display">&laquo; Kembali ke halaman hasil pencarian</a></strong></legend>
			<label>Pelanggan : </label>
			<?php 
			$Q = mysql_query("SELECT * FROM pelanggan WHERE id_pelanggan = {$dataRinci->id_pelanggan}");
			$P = (object) mysql_fetch_assoc($Q);
			?>
			<input type="text" value="<?php echo $P->nama_pelanggan;?>" disabled />
			
			<label>Keluhan :</label>
			<textarea disabled name="keluhan" cols="60" rows="10"><?php echo $dataRinci->keluhan;?></textarea>
			<label>Estimasi waktu pengerjaan <em>(Hari)</em> : </label>
			<input type="text" class="nomor" disabled name="estimasi_pekerjaan" value="<?php echo $dataRinci->estimasi_pekerjaan;?>"/> 
			<label>Status : </label>
			<input type="text" disabled value="<?php echo $dataRinci->status_servis;?>" />
			
			<hr />
			
			<label>Tipe pekerjaan : </label>
			
			<table>
				<thead>
					<tr>
						<th>Pilihan</th>
						<th>Nama Pekerjaan</th>
						<th>Yang Mengerjakan</th>
						<th>Harga Jasa</th>
					</tr>
				</thead>
				<tbody>
				<?php 
				$sql_pekerjaan = "
						SELECT tipe_pekerjaan.*,
						IF(servis_rinci_pekerjaan.id_pekerjaan>0,servis_rinci_pekerjaan.id_pekerjaan,0) AS idpekerjaan ,
						IF(servis_rinci_pekerjaan.id_servis={$id_serv},'checked','') AS terpilih FROM tipe_pekerjaan 
						LEFT JOIN servis_rinci_pekerjaan ON 
						(servis_rinci_pekerjaan.id_tipe_pekerjaan = tipe_pekerjaan.id_tipe_pekerjaan AND servis_rinci_pekerjaan.id_servis = {$id_serv})  
						";
				$Q = mysql_query($sql_pekerjaan)or die(mysql_error().$sql_pekerjaan);
				
				$P = mysql_query("SELECT * FROM karyawan");
				$no = 0;
				$pekerja = array();
				while($p = mysql_fetch_object($P)):$pekerja[] = $p;endwhile;
				
				$no = 0;
				$totalJasa = 0;
				while($t = mysql_fetch_object($Q)):
				
					if($t->terpilih == 'checked')
					{
						$totalJasa+=$t->harga_jasa_pekerjaan;
					}
					else
					{
						continue;
					}
			?>
					<tr>
						<td><input type="checkbox" disabled <?php echo $t->terpilih;?> name="id_tipe_pekerjaan[<?php echo $no;?>]" value="<?php echo $t->id_tipe_pekerjaan;?>" /></td>
						<td><?php echo $t->nama_tipe_pekerjaan;?></td>
						<td>
							
							<?php foreach($pekerja as $v => $x):?>
							
								<?php $sql_pekerja = mysql_query("SELECT * FROM servis_pekerja WHERE id_pekerjaan = {$t->idpekerjaan} AND id_karyawan = {$x->id_karyawan}");?>
							
									<?php if(mysql_num_rows($sql_pekerja) > 0)echo $x->nama_karyawan. " <em>({$x->jabatan})</em><hr />";?>
									
							<?php endforeach;?>
							
						</td>
						<td><?php echo $t->harga_jasa_pekerjaan;?></td>
					</tr>
				<?php 
				$no++;
				endwhile;
				?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="2"></th>
						<th>Total Harga Jasa</th>
						<th class="kiri"><?php echo $totalJasa;?></th>
					</tr>
				</tfoot>
			</table>
			
			
			<hr />
			<label>Komponen yang diganti: </label>
			
			
			<table>
				<thead>
					<tr>
						<th>Kode</th>
						<th>Nama</th>
						<th>Harga</th>
						<th>Jumlah Persediaan / stok</th>
						<th>Tipe</th>
						<th>Jumlah Yang diganti</th>
					</tr>
				</thead>
				<tbody>
					<?php  
					$totalBarang = 0;
					$Q = mysql_query(
					"
					SELECT 
					barang.*,
					tipe_barang.nama_tipe_barang,
					IF(servis_komponen.id_servis = {$id_serv},servis_komponen.jumlah_barang,0) AS jumlah
					FROM barang 
					LEFT JOIN tipe_barang ON tipe_barang.id_tipe_barang = barang.id_tipe_barang
					LEFT JOIN servis_komponen ON 
					(servis_komponen.kode_barang = barang.kode_barang AND servis_komponen.id_servis = {$id_serv}) 
					")or die(mysql_error()); 
					$no = 0;
					while($b = mysql_fetch_object($Q)):
					
						if($b->jumlah < 1)continue;
						echo "
						<tr>
							<td>{$b->kode_barang}</td>
							<td>{$b->nama_barang}</td>
							<td>{$b->harga}</td>
							<td>{$b->stok}</td>
							<td>{$b->nama_tipe_barang}</td>
							<td>
								<input disabled type='text' class='nomor' name='jumlah_barang[{$no}]' value='{$b->jumlah}' />
							</td>
						</tr>
					";
					if($b->jumlah > 0 )$totalBarang+=$b->harga*$b->jumlah;
					$no++;
					
					endwhile;
					?>
					
				</tbody>
				<tfoot>
					<tr>
						<th colspan="2">Total Harga Barang :</th>
						<th><?php echo $totalBarang;?></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</tfoot>
			</table>
			
			<h2 class='kanan'> Total Harga servis : Rp. <?php echo $totalBarang+$totalJasa;?></h2>
			<hr />
			<a href="javascript:window.print();return false;" class="display tombol kanan">Cetak</a>
	</fieldset>
</form>
	

<?php else:?>

<!-- HASIL PENCARIAN  -->


<fieldset>
	<legend>Hasil Pencarian Pelanggan dengan ID <?php echo $_GET['id_pelanggan'];?></legend>
		<table>
			<thead>
				<tr>
					<th>Tanggal</th>
					<th>Keluhan</th>
					<th width="100">Waktu Pengerjaan</th>
					<th>Status Servis</th>
					<th>Nama Pelanggan</th>
					<th>Id Pelanggan</th>
					<th>Pengaturan</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$Q = mysql_query("SELECT servis.*,pelanggan.nama_pelanggan FROM servis LEFT JOIN pelanggan ON pelanggan.id_pelanggan = servis.id_pelanggan WHERE servis.id_pelanggan = {$_GET['id_pelanggan']}");
			while($o = mysql_fetch_object($Q)):
			?>
				<tr>
					<td><?php echo $o->tanggal_servis;?></td>
					<td><?php echo $o->keluhan;?></td>
					<td><?php echo $o->estimasi_pekerjaan;?> Hari</td>
					<td><?php echo $o->status_servis;?></td>
					<td><?php echo $o->nama_pelanggan;?></td>
					<td><?php echo $o->id_pelanggan;?></td>
					<td>
						<a href="?halaman=konsumen&id_pelanggan=<?php echo $o->id_pelanggan;?>&lihat_id=<?php echo $o->id_servis;?>">Lihat Rinci</a>
					</td>
				</tr>
			<?php 
			endwhile;
			?>
			</tbody>
		</table>
</fieldset>

<?php endif;?>
<?php endif;?>