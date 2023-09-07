<?php 
require_once('../config/config.php');
require_once('../models/database.php');
include('../models/m_btb.php');

if(@$_GET['agent_name']){
	
	$connection = new Database($host, $user, $pass, $database);
	$class = new Btb($connection);
	if($class->add_agent_name($_GET['agent_name'])){
		$status = [
			'status' => 'success',
		];

		echo json_encode($status);
	}else{
		$status = [
			'status' => 'error',
		];

		echo json_encode($status);
	}
}
?>