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

if (@$_POST['delete_agent']) {
	include('../models/m_agent.php');
	$connection = new Database($host, $user, $pass, $database);
	$class = new Agent($connection);
	$agent_id = $_POST['id'];
	$delete = $class->deleteAgent($agent_id);
	header('Content-Type: application/json');
	http_response_code(404);
	if ($delete == 'deleted') {
		http_response_code(200);
		echo json_encode(['status' => 200, 'message' => 'deleted']);
	} else {
		http_response_code(404);
		echo json_encode(['status' => 404, 'message' => $delete->getMessage()]);
	}
}

if (@$_POST['add_agent']) {
	include('../models/m_agent.php');
	$connection = new Database($host, $user, $pass, $database);
	$class = new Agent($connection);
	$agent_name = $_POST['agent_name'];
	$agent_npwp = $_POST['agent_npwp'];
	$agent_address = $_POST['agent_address'];
	$data = [
		'agent_name' => $agent_name,
		'agent_npwp' => $agent_npwp,
		'agent_address' => $agent_address
	];

	$insert = $class->insertAgent($data);
	header('Content-Type: application/json');
	http_response_code(404);
	if ($insert == 'inserted') {
		http_response_code(200);
		echo json_encode(['status' => 200, 'message' => 'inserted']);
	} else {
		http_response_code(404);
		echo json_encode(['status' => 404, 'message' => $insert->getMessage()]);
	}
}
