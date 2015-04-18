<?php

$data_pengguna = isset($_COOKIE['data_pengguna']) && $_COOKIE['data_pengguna'] != '' ? $_COOKIE['data_pengguna'] : FALSE;  
if(HALAMAN_AKTIF != 'masuk' && HALAMAN_AKTIF != 'konsumen' && !$data_pengguna)
{
	header('location:?halaman=masuk');
	exit();
}
else
{
	if(isset($_POST['kode']) && isset($_POST['kunci']))
	{
		$kode = mysql_real_escape_string($_POST['kode']);
		$kunci = mysql_real_escape_string($_POST['kunci']);
		$sqlCheck = mysql_query("SELECT * FROM pengguna WHERE kode_pengguna = '{$kode}' AND kata_kunci = '{$kunci}'");
		
		if(mysql_num_rows($sqlCheck) != 1)
		{
			echo '<hr /><h2>Kode atau Kata kunci pengguna salah !. <small>Silahkan ulangi lagi.</small></h2><hr />';
			
		}
		else
		{
			$dataPengguna = (object) mysql_fetch_assoc($sqlCheck);
			setcookie('data_pengguna',json_encode($dataPengguna));
			
			header('location:?halaman=beranda');
			exit();
		}
	}
	else
	{
		if(HALAMAN_AKTIF == 'masuk')
		{
			$_COOKIE['data_pengguna'] = FALSE;
			setcookie('data_pengguna','');
		}
		
	}
}
