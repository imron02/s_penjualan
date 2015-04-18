<?php 
error_reporting(0);
$disabled = '';
if($_POST['act']=="simpan")
{
	db::query()->simpan('transaksi',array(
		'id_pelanggan'=>$_POST['id_pelanggan'],
		'tipe_transaksi'=>'penjualan'
	),
	FALSE);
	$id = mysql_insert_id($koneksi);
	foreach($_POST['kode_barang'] as $no => $kode_barang)
	{
		if((int)$_POST['banyaknya'][$no] > 0)
		{
			db::query()->simpan('transaksi_rinci',
					array(
							'kode_barang'=>$kode_barang,
							'banyaknya'=>mysql_real_escape_string(str_replace(",", "", $_POST['banyaknya'][$no])),
							'harga_saatini'=>$_POST['harga_saatini'][$no],
							'jam'=>date("H:i:s",time()),
							'id_transaksi'=>$id,
							'diskon'=>$_POST['diskon'][$no]
					)
					,FALSE);
			mysql_query("UPDATE barang SET stok = stok - ".mysql_real_escape_string($_POST['banyaknya'][$no])." WHERE kode_barang = '{$kode_barang}'");
			
			$total = $_POST['banyaknya'][$n]* $_POST['harga_saatini'][$n];
		    $potonganDiskon = ($total * $_POST['diskon'][$n]) / 100;
		    $subTotal = $total - $potonganDiskon;
		
			mysql_query("INSERT INTO history_harga (id_pelanggan, kode_barang, barang, harga_barang) values ('".$_POST['id_pelanggan']."', '".$_POST['kode_barang']."', '".$_POST['nama_barang']."', '".$subTotal."'");
		}
		
	}
	
	header("location:?halaman=invoice&id_transaksi={$id}");
	exit();
}?>
	<fieldset>
		<legend>Tambah Penjualan</legend>

<?php
		$id_pelanggan = $_POST['id_pelanggan'];
		if ($id_pelanggan=="") {
?>
		<form action="index.php?halaman=penjualan" method="post" class="formulir-umum" enctype="multipart/form-data">
		<label>Pelanggan : </label>
		<select name="id_pelanggan" >
			<?php 
			$id_pelanggan;
			$Q = mysql_query("SELECT * FROM pelanggan");
			while ($c = mysql_fetch_object($Q)):
				echo '<option value="'.$c->id_pelanggan.'">'.$c->nama_pelanggan.'</option>';
			endwhile;
			
			?>
		</select>
		<button type="submit" >Pilih</button>
		</form>
<?php	
		}
		elseif ($id_pelanggan!="") {
?>
		<form action="" method="post" class="formulir-umum" enctype="multipart/form-data">
			<select name="id_pelanggan" >
			<?php 
			$id_pelanggan;
			$Q = mysql_query("SELECT * FROM pelanggan WHERE id_pelanggan=$id_pelanggan");
			while ($c = mysql_fetch_object($Q)):
				echo '<option value="'.$c->id_pelanggan.'">'.$c->nama_pelanggan.'</option>';
			endwhile;
			
			?>
		</select>
		<label>Pelanggan : </label>
		<hr />
		<label>Pilih barang :</label>
		<table width="530">
			<thead>
				<tr>
					<th width="40">Kode</th>
					<th width="45">Nama</th>
					<th width="100">Harga (Rp.)</th>
					<th width="100">Jumlah Persediaan / stok</th>
					<th width="34">Tipe</th>
					<th width="40">Jumlah Pembelian</th>
					<th width="50">Diskon Per Item %</th>
                    <!--<th width="50">Status Pembelian</th>-->
                    <th width="50">Riwayat Harga</th>
				</tr>
			</thead>
			<tbody>
				<?php  
				$Q = mysql_query("SELECT barang.*,tipe_barang.nama_tipe_barang FROM barang LEFT JOIN tipe_barang ON tipe_barang.id_tipe_barang = barang.id_tipe_barang"); 
				$n = 0;
				while($o = mysql_fetch_object($Q)):
				
					echo "
					<tr>
						<td>{$o->kode_barang}</td>
						<td>{$o->nama_barang}</td>
						<td align='right'>".number_format($o->harga)."</td>
						<td align='right'>".number_format($o->stok)."</td>
						<td>{$o->nama_tipe_barang}</td>
						<td>
							<input type='hidden' name='kode_barang[{$n}]' value='{$o->kode_barang}' />
							<input type='hidden' name='harga_saatini[{$n}]' value='{$o->harga}' />
							<input type='text' class='nomor' name='banyaknya[{$n}]' value='0' size='5' onkeyup='this.value=addCommas(this.value);' />
						</td>
						<td>
						<input type='text' name='diskon[{$n}]' class='nomor' value='0' size='5' />
						</td>
						<td>"
						/* >>> F was here
						<td>
						<select name='status'>
							<option value='lunas'>Bayar</option>
							<option value='belum_lunas'>Tunggakan</option>
						</select>
						</td>
						*/;

						

						?>
						<?php
						//$query = mysql_query("SELECT hg.harga_barang, p.nama_pelanggan, p.id_pelanggan from history_harga hg LEFT JOIN pelanggan p ON p.id_pelanggan = hg.id_pelanggan WHERE hg.kode_barang = '{$o->kode_barang}' AND hg.id_pelanggan=$id_pelanggan");
						$query = mysql_query("SELECT * FROM `transaksi_rinci` WHERE kode_barang = '{$o->kode_barang}' AND id_pelanggan = $id_pelanggan ORDER BY updated DESC LIMIT 5");
						echo "<select name='harga_lama'>";
						echo "<option value=''>- Pilih harga -</option>";
						$i=0;
						while ($row = mysql_fetch_array($query)){
							if($i>4){
								break;
							}
							echo "<option value=$row[harga_saatini]>Rp. ".number_format($row[harga_saatini])."</option>";
						
							$i++;
						}
						echo "</select></td>
					</tr>";
					$n++;
				endwhile;
				?>
			</tbody>
		</table>

		<hr />
		<button type="submit" class="tombol" name="act" value="simpan">Simpan</button>
		<a href="?halaman=belanja" class="tombol">Kembali</a>
		<hr />
		</form>
<?php
		}
?>
	</fieldset>
<script type="text/javascript">
	function addCommas(nStr) {
		x = nStr.replace(/,/g, "");
		return (x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
	}
</script>