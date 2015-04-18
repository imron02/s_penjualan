<?php 

$id_transaksi = isset($_GET['id_transaksi']) && (int) $_GET['id_transaksi'] > 0 ? $_GET['id_transaksi'] : FALSE;

if(isset($_GET['hapus']) && $_GET['hapus'] = 1)
{
	mysql_query("DELETE FROM transaksi WHERE id_transaksi = '{$id_transaksi}'");
	mysql_query("DELETE FROM transaksi_rinci WHERE id_transaksi = '{$id_transaksi}'");
	header("location:?halaman=laporan&jenis_laporan={$_GET['jenis_laporan']}");
}


?>

<h1 class="sub-judul">Hapus Laporan Transaksi</h1>

Anda yakin akan menghapus transaksi ini?
<hr />
<a class="tombol" href="?halaman=laporan_hapus&jenis_laporan=<?php echo $_GET['jenis_laporan']?>&id_transaksi=<?php echo $id_transaksi;?>&hapus=1">Ya Hapus</a> 
<a class="tombol" href="?halaman=laporan&jenis_laporan=<?php echo $_GET['jenis_laporan']?>">Batalkan</a>  