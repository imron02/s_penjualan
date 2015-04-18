<?php
include DIREKTORI . 'sistem/konfigurasi.php';

$halaman = isset($_GET['halaman']) && $_GET['halaman'] != '' && file_exists(DIR_APP .  'halaman_' . $_GET['halaman'] . '.php' ) ? $_GET['halaman'] : 'beranda';

define('HALAMAN_AKTIF' , $halaman);
define('TEMA_HALAMAN', DIR_APP . 'tema_utama.php' );
define('PANGGIL_HALAMAN', DIR_APP . 'halaman_' .HALAMAN_AKTIF . '.php' );

require_once TEMA_HALAMAN;
