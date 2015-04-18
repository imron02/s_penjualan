<h1 class="sub-judul">Daftar Belanja</h1>


<?php 

	if(isset($_GET['perbarui_id']) && $_GET['perbarui_id'] > 0)
	{
		require_once DIR_APP . 'data_belanja_rinci_perbarui.php';
	}
	else if(isset($_GET['hapus_id']) && $_GET['hapus_id'] > 0)
	{
		require_once DIR_APP . 'data_belanja_rinci_hapus.php';
	}
	else if(isset($_GET['tambah']) && $_GET['tambah'] > 0)
	{
		require_once DIR_APP . 'data_belanja_tambah.php';
	}
	else
	{
		require_once DIR_APP . 'data_belanja.php';
	}
	
?>