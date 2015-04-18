<?php

$halaman_laporan = isset($_GET['jenis_laporan']) && file_exists( DIR_APP . 'data_laporan_' . $_GET['jenis_laporan'] . '.php') ? DIR_APP . 'data_laporan_' . $_GET['jenis_laporan'] . '.php' : DIR_APP . 'data_laporan.php';

require_once $halaman_laporan;