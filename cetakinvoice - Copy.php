<?php
function membetulkan($nilai,$batasan,$align)
{
	$panjangNilai = strlen($nilai);
	if ($panjangNilai>$batasan) {
		$nilai = substr($nilai, 0, $batasan);
	}
	else{
		if ($align=="L"||$align=="l") {
			$nilai = $nilai . str_repeat(" ", $batasan - $panjangNilai);
		}
		else {
			$nilai = str_repeat(" ", $batasan - $panjangNilai) . $nilai;
		}
	}
	return $nilai;
}

error_reporting(0);
session_start();
mysql_connect("localhost","root","");
mysql_select_db("s_penjualan");
//require ("fungsi/html2pdf/html2pdf.class.php");
//$content = ob_get_clean();
$query = "SELECT 
				* 
			FROM 
				transaksi t
			LEFT JOIN
				pelanggan p ON p.id_pelanggan=t.id_pelanggan 
			WHERE 
				t.tipe_transaksi = 'penjualan' AND
				t.id_transaksi = '".$_GET['id_transaksi']."';";
$result = mysql_query($query);
$data_transaksi = mysql_fetch_assoc($result);
$query = "SELECT
					t.*,b.nama_barang,tb.*
				FROM
					transaksi_rinci t
				LEFT JOIN 
					barang b ON b.kode_barang = t.kode_barang
				LEFT JOIN 
					tipe_barang tb ON tb.id_tipe_barang = b.id_tipe_barang
				WHERE
					t.id_transaksi = '".$_GET['id_transaksi']."'";
$result = mysql_query($query);

$header = "PT.WECO BINA UTAMA
Jl.Roa Malaka II No.10-JAKARTA BARAT
Telp. 021-6901655
Fax. 021-6929219
                                  FAKTUR PENJUALAN
ID Transaksi      : {$data_transaksi['id_transaksi']}
Tanggal Transaksi : {$data_transaksi['tanggal_transaksi']}
Pelanggan         : {$data_transaksi['nama_pelanggan']}
------------------------------------------------------------------------------------
No.  No. Item  Nama  Barang      Qty  Satuan  Disc            Harga            Total
------------------------------------------------------------------------------------";
$keseluruhan = 0;
$totalDiskon = 0;
$i = 0;
$hslPrint="$header";
while ($data_rinci = mysql_fetch_assoc($result)) {
	$total = $data_rinci['banyaknya']*$data_rinci['harga_saatini'];
	$potonganDiskon = ($total * $data_rinci['diskon']) / 100;
	$subTotal = $total - $potonganDiskon;
	$keseluruhan+=$subTotal;

	$no = membetulkan(($i+1),3,"R");
	$noItem = membetulkan($data_rinci['kode_barang'],8,"L");
	$namaBrg = membetulkan($data_rinci['nama_barang'],16,"L");
	$qty = membetulkan($data_rinci['banyaknya'],3,"R");
	$satuan = membetulkan($data_rinci['nama_tipe_barang'],6,"R");
	$disc = membetulkan($data_rinci['diskon'],3,"R");
	$harga = membetulkan(number_format($data_rinci['harga_saatini'],0,',','.'),13,"R");
	$ptotal = membetulkan(number_format($subTotal,0,',','.'),13,"R");

	$items = "$noItem  $namaBrg  $qty  $satuan  $disc%  Rp$harga  Rp$ptotal";
	if ($i>0 && $i%17==0) {
		$hslPrint.="\n\n\n$header";
		$hslPrint.="\n$no  $items";
	}
	else{
		$hslPrint.="\n$no  $items";
	}
	$i++;
}


$diskon_keseluruhan_nominal = $keseluruhan * $data_transaksi['diskon_keseluruhan'] / 100;
$ppn_nominal = ($keseluruhan - $diskon_keseluruhan_nominal) * $data_transaksi['ppn'] / 100;
$grand_total = $keseluruhan - $diskon_keseluruhan_nominal + $ppn_nominal;

$discount = membetulkan($data_transaksi['diskon_keseluruhan'],3,"R");
$discountTotal = membetulkan(number_format($diskon_keseluruhan_nominal,0,',','.'),13,"R");
$ppn = membetulkan($data_transaksi['ppn'],3,"R");
$ppnTotal = membetulkan(number_format($ppn_nominal,0,',','.'),13,"R");
$grandTotal = membetulkan(number_format($grand_total,0,',','.'),13,"R");
$pembayaran = membetulkan(number_format($data_transaksi['pembayaran'],0,',','.'),13,"R");
$tunggakan = membetulkan(number_format($data_transaksi['tunggakan'],0,',','.'),13,"R");

$jatuhTempo = "Jatuh Tempo:".$data_transaksi['jatuh_tempo'];
$keseluruhan = membetulkan(number_format($keseluruhan,0,',','.'),13,"R");

$footer = "\n------------------------------------------------------------------------------------
$jatuhTempo                      Total  :                 Rp$keseluruhan
Mengetahui/Menyetujui                    Discount  :           $discount%  Rp$discountTotal
                                              PPN  :           $ppn%  Rp$ppnTotal
                                    ------------------------------------------------
                                      Grand Total  :                 Rp$grandTotal
                                       Pembayaran  :                 Rp$pembayaran
                                    ------------------------------------------------
(...................)                   Tunggakan  :                 Rp$tunggakan";
$hslPrint.="$footer";
/*
if (($i+11)<28) {
	$kurangnya = 28-($i+11);
	$hslPrint.=str_repeat("\n", $kurangnya);
}*/

$handle=printer_open("EPSON LX-310 ESC/P");
// print_r($handle);
// die();
// $handle=printer_open("EPSON LX-300+ /II (Copy 1)");
printer_write($handle,$hslPrint);
printer_close($handle);

?>
printing....