<?php
class db
{
	function __construct()
	{
		
	}
	
	static function query()
	{
		return new self;
	} 
	
	function simpan( $namaTable ,$data = array(),$direct = TRUE)
	{
		// print_r($namaTable);
		// die();
		$dataKolom = array();
		$dataIsiKolom = array();
		
		if(!$data)$data = $_POST ;
		
		foreach($data as $indeks => $isi)
		{
			$dataKolom[] = $indeks;
			$dataIsiKolom[] = mysql_real_escape_string($isi);
		}
		
		$kolom = implode("`,`",$dataKolom);
		$IsiKolom = implode("','",$dataIsiKolom);
		
		$Q = mysql_query("INSERT INTO `{$namaTable}` (`{$kolom}`) VALUES ('{$IsiKolom}') ")or die(mysql_error());
		if($Q && $direct === TRUE)
		{
			header('location:?halaman='.HALAMAN_AKTIF);
			exit();
		}
		else
		{
			return mysql_insert_id();
		}
	}
	
	function perbarui( $namaTable , $data = array() , $persyaratan = array())
	{
		$dataKolom = array();
		$syarat = array();
		if(!$data)$data = $_POST ;
		
		
		foreach($persyaratan as $a => $b)
		{
			$syarat[] = $a . "='".mysql_real_escape_string($b)."'";
		}
		

		foreach($data as $indeks => $isi)
		{
			$dataKolom[] = $indeks . "= '" . mysql_real_escape_string($isi) . "'";
		}
		
		$sql = "UPDATE `{$namaTable}` SET ".implode(",",$dataKolom);
		$sqlTambahan = " WHERE ".implode(" AND ",$syarat);
		if(count($syarat) > 0)$sql .= $sqlTambahan;
		
		if($data)
		{
			$Q = mysql_query($sql)or die(mysql_error());
			
			if($Q)
			{
				header('location:?halaman='.HALAMAN_AKTIF);
				exit();
			}	
		}
		
		$dataTable = mysql_query("SELECT * FROM {$namaTable} {$sqlTambahan} LIMIT 1");
		$dataOutput = (object) mysql_fetch_assoc($dataTable);
		return $dataOutput;
	}
	
	function cekId( $namaTable , $persyaratan = array())
	{
		$syarat = array();
		foreach($persyaratan as $a => $b)
		{
			$syarat[] = $a . "='".mysql_real_escape_string($b)."'";
		}
		$sql = "SELECT COUNT(*) AS `baris` FROM `{$namaTable}`";
		if(count($syarat) > 0)$sql .= " WHERE ".implode(" AND ",$syarat);
		
		$q = mysql_query($sql)or die(mysql_error() . '<br />' . $sql);
		$o = mysql_fetch_assoc($q);
		return (int) $o['baris'];
	}
}