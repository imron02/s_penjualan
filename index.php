<?php 
ob_start();
define('DIREKTORI', str_replace( '\\','/', pathinfo( __FILE__, PATHINFO_DIRNAME ) . '/'  )  );

require_once DIREKTORI . 'sistem/kontroler.php';
