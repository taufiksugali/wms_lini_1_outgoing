<?php 
require_once('../config/config.php');
require_once('../models/database.php');
include('../models/m_flight.php');

if(@$_GET['smu_code']){
	// echo "123";
	$connection = new Database($host, $user, $pass, $database);
	$class = new Flight($connection);
	$method = $class->get_flight_by_smu_code($_GET['smu_code']);
	// var_dump($method);
	echo json_encode($method->fetch_all());
}
?>