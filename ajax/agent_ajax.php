<?php
require_once('../config/config.php');
require_once('../models/database.php');
include('../models/m_btb.php');

if (@$_GET['agent_name']) {

	$connection = new Database($host, $user, $pass, $database);
	$class = new Btb($connection);
	if ($class->add_agent_name($_GET['agent_name'])) {
		$status = [
			'status' => 'success',
		];

		echo json_encode($status);
	} else {
		$status = [
			'status' => 'error',
		];

		echo json_encode($status);
	}
}

if (@$_POST['save_npwp']) {
	$connection = new Database($host, $user, $pass, $database);
	$class = new Btb($connection);
	$agent_id = $_POST['agent_id'];
	$npwp = $_POST['npwp'];
	header('Content-Type: application/json');
	if ($class->updateNPWP($agent_id, $npwp)) {
		echo json_encode(['status' => 'success', 'npwp' => $npwp]);
	} else {
		echo json_encode(['status' => 'error']);
	}
}
