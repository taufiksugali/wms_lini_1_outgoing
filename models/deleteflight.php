<?php 
if(!isset($_GET['data'])){
	header('location: ../?page=addflight');
}
require_once('../config/config.php');
require_once('database.php');
include('m_btb.php');
$connection = new Database($host, $user, $pass, $database);
$datax = new Btb($connection);
$data = $_GET['data'];
$proses = $datax->deleteflight($data);
if(!$proses){
	header('location: ../?page=addflight&proses=gagal');
}else{
	header('location: ../?page=addflight&proses=berhasil');	
}


?>