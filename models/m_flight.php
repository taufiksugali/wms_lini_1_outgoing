<?php 
class Flight{
	private $mysqli;
	function __construct($conn){
		$this->mysqli = $conn;
	}

	public function get_flight_by_smu_code($smu_code)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT `flight`.*
		FROM `flight`
		JOIN `airlines` ON `airlines`.`airline_id` = `flight`.`airline_id`
		JOIN `smu_code` ON `smu_code`.`airline_id` = `airlines`.`airline_id`
		WHERE `smu_code`.`code` = '".$smu_code."'";
		$query = $db->query($sql);

		return $query;
	}
	public function get_flight_airline_by_tlc($tlc, $airline)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT `flight`.*
		FROM `flight`
		JOIN `airlines` ON `airlines`.`airline_id` = `flight`.`airline_id`
		JOIN `smu_code` ON `smu_code`.`airline_id` = `airlines`.`airline_id`
		WHERE `flight`.`airline_id` = '".$airline."' AND `flight`.`tlc` = '".$tlc."'";
		$query = $db->query($sql);

		return $query;
	}
}
?>