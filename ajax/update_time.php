<?php 
require_once('../config/config.php');
require_once('../models/database.php');
include('../models/m_report.php');
$connection = new Database($host, $user, $pass, $database);
$report  = new Report($connection);
$id = $_POST['id'];
$time = $_POST['time'];
if($report->updatePaymentTime($id, $time)){
	$respond = [
		'status' => 200,
	];
	echo json_encode($respond);
}else{
	$respond = [
		'status' => 500,
	];
	echo json_encode($respond);
}


?>