<?php
//koneksi ke web server lokal
$myHost = "localhost";
$myUser = "root";
$myPass = "password";
$myDbs = "s_penjualan";
// konek ke Web Server lokal
$koneksidb = mysql_connect($myHost, $myUser, $myPass);
if(! $koneksidb){
	echo "Failed Connection !";
}
// Memilih database pada MySQL Server
mysql_select_db($myDbs) or die ("Database not Found !");
?>
