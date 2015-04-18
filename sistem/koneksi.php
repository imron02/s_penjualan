<?php 
$server = 'localhost';
$pengguna = 'root';
$kata_kunci = 'password';
$nama_database = 's_penjualan';

$koneksi = mysql_connect($server,$pengguna,$kata_kunci)or die(mysql_error());

mysql_select_db($nama_database,$koneksi)or die(mysql_error());

include DIR_SISTEM . 'database.php';
