<?php
class Airline
{
	private $mysqli;
	function __construct($conn)
	{
		$this->mysqli = $conn;
	}

	public function call_all_airline()
	{
		$db = $this->mysqli->conn;
		$sql = 'SELECT * FROM airlines GROUP BY airline_name';
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function add_new_airline($data)
	{
		$db = $this->mysqli->conn;
		$sql = 'INSERT INTO `airlines` (airline_id, airline_name) VALUES ("' . $data['airline_id'] . '","' . $data['airline_name'] . '")';
		$query = $db->query($sql);

		return ($query);
	}

	public function call_all_airline_ungroup()
	{
		$db = $this->mysqli->conn;
		$sql = 'SELECT * FROM airlines';
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function getScheduleFlight($airlineId, $date)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT *, `flight`.`id` as `flight_id`
				FROM `airlines`
				JOIN `flight` ON `flight`.`airline_id` = `airlines`.`airline_id`
				LEFT JOIN `schedule` ON `schedule`.`flight_id` = `flight`.`id` AND `schedule`.`schedule_date` = '$date'
				WHERE `airlines`.`airline_id` = '$airlineId'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function getAirlineById($airlineId)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM `airlines` WHERE `airline_id` = '$airlineId'";
		$query = $db->query($sql)->fetch_object() or die($db->error);

		return ($query);
	}
}
