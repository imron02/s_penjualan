<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>SISTEM INFORMASI PT WECO BINA UTAMA</title>
	<link rel="stylesheet" href="./publik/tema/tema.css" />
	<script type="text/javascript" src="./publik/javascript/javascript.js"></script>
	<script type="text/javascript" src="./publik/javascript/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="./publik/javascript/jquery.number.min.js"></script>
</head>
<body>
	<ul class="navigasi kiri">
		<li><a href="?halaman=beranda" class="logo">SELAMAT DATANG</a></li>
		<?php if(HALAMAN_AKTIF != 'masuk'):?>
		<li><a href="#">Data Master</a>
			<ul>
				<li><a href="?halaman=barang" <?php if(HALAMAN_AKTIF == 'barang')echo 'class="aktif"';?>>Barang</a></li>
				<li><a href="?halaman=tipe_barang" <?php if(HALAMAN_AKTIF == 'tipe_barang')echo 'class="aktif"';?>>Tipe Barang</a></li>
				<li><a href="?halaman=pelanggan" <?php if(HALAMAN_AKTIF == 'pelanggan')echo 'class="aktif"';?>>Pelanggan</a></li>
				<li><a href="?halaman=pengguna" <?php if(HALAMAN_AKTIF == 'pengguna')echo 'class="aktif"';?>>Pengguna</a></li>
				<li><a href="?halaman=supplier" <?php if(HALAMAN_AKTIF == 'supplier')echo 'class="aktif"';?>>Pemasok / Supplier</a></li>
			</ul>
		</li>
		<li><a href="#">Transaksi</a>
			<ul>
				<li><a href="?halaman=penjualan">Transaksi Penjualan</a></li>
				<li><a href="?halaman=pembelian">Transaksi Pembelian</a></li>
				<li><a href="?halaman=invoice">Invoice</a></li>
				<li><a href="?halaman=surat_jalan">Surat Jalan</a></li>
			</ul>
		</li>
		<li><a href="?halaman=laporan">Laporan</a>
			<ul>
				<li><a href="?halaman=laporan&jenis_laporan=penjualan">Laporan Penjualan</a></li>
				<li><a href="?halaman=laporan&jenis_laporan=pembelian">Laporan Pembelian</a></li>
				<li><a href="?halaman=laporan&jenis_laporan=stok">Laporan Stok Barang</a></li>
				<li><a href="?halaman=laporan&jenis_laporan=piutang">Laporan Piutang</a></li>
			</ul>
		</li>
		<li><a href="?halaman=masuk">Keluar</a></li>
		<?php endif;?>
	</ul>
	<div class="konten kiri">
		<div class="konten-utama">
			<div class="judul">
				<h4>PT WECO BINA UTAMA
				<br>Jl. Roa Malaka II no.10 - JAKARTA BARAT<br>Telp. 021-6901655<br>Fax. 021-6929219</h4>
			</div>
			<div class="isi-konten">
				<?php require_once DIR_APP . 'proteksi.php';?>
				<?php require_once PANGGIL_HALAMAN; ?>
			</div>
		</div>
	</div>
</body>
</html>
