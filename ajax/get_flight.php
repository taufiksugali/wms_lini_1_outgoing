<?php
require_once('../config/config.php');
require_once('../models/database.php');
include('../models/m_flight.php');

if (@$_GET['smu_code']) {
	// echo "123";
	$connection = new Database($host, $user, $pass, $database);
	$class = new Flight($connection);
	$method = $class->get_flight_by_smu_code($_GET['smu_code']);
	// var_dump($method);
	echo json_encode($method->fetch_all());
}

if (@$_GET['action']) {
	if ($_GET['action'] == 'save_schedule') {
		$connection = new Database($host, $user, $pass, $database);
		$flightClass = new Flight($connection);

		$flightId = $_POST['flight_id'];
		$date = $_POST['date'];
		$time = $_POST['time'];

		$save =  $flightClass->save_schedule($flightId, $date, $time);

		if (@$save->schedule_id) {
			$respond = [
				'status' => 200,
				'data' => $save
			];

			header('Content-Type: application/json');
			http_response_code(200);
			echo json_encode($respond);
		} else {
			$respond = [
				'status' => 500,
				'message' => 'Schedule not saved, please try again!'
			];

			header('Content-Type: application/json');
			http_response_code(500);
			echo json_encode($respond);
		}
	}
}
