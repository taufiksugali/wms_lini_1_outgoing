<?php
require_once('../config/config.php');
require_once('../models/database.php');
include('../models/m_btb.php');

if(@$_GET['ra_name']){
	$connection = new Database($host, $user, $pass, $database);
	$class = new Btb($connection);
	$exist_data = $class->check_ra_name($_GET['ra_name']);

	if($exist_data === false){
		$insert = $class->insert_new_regulated_agent($_GET['ra_name']);
		$new_data = $class->check_ra_name($_GET['ra_name']);

		$respond = [
			'status' => 200,
			'ra_id' => $new_data->ra_id,
			'ra_name' => $new_data->ra_name,
		];
		echo json_encode($respond);
	}
}
?>