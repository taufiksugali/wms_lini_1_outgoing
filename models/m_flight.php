<?php
class Flight
{
	private $mysqli;
	function __construct($conn)
	{
		$this->mysqli = $conn;
	}

	public function get_flight_by_smu_code($smu_code)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT `flight`.*
		FROM `flight`
		JOIN `airlines` ON `airlines`.`airline_id` = `flight`.`airline_id`
		JOIN `smu_code` ON `smu_code`.`airline_id` = `airlines`.`airline_id`
		WHERE `smu_code`.`code` = '" . $smu_code . "'";
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
		WHERE `flight`.`airline_id` = '" . $airline . "' AND `flight`.`tlc` = '" . $tlc . "'";
		$query = $db->query($sql);

		return $query;
	}

	public function save_schedule($flightId, $date, $time)
	{
		$db = $this->mysqli->conn;
		$sql = "INSERT INTO `schedule`(`flight_id`, `schedule_date`, `schedule_time`) VALUES ('" . $flightId . "','" . $date . "','" . $time . "')";
		$query = $db->query($sql);

		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM `schedule` WHERE `flight_id` = '$flightId' AND `schedule_date` = '$date' AND `schedule_time` = '$time'";
		$query = $db->query($sql)->fetch_object() or die($db->error);
		return ($query);
	}

	public function update_schedule($scheduleId, $data)
	{
		try {
			$number = 0;
			$string = '';
			foreach ($data as $column => $value) {
				if ($number == 0) {
					$string .= $column . "='" . $value . "'";
				} else {
					$string .= ", " . $column . "='" . $value . "'";
				}
				$number++;
			}
			$db = $this->mysqli->conn;
			$sql = "UPDATE schedule SET ";
			$sql .= $string;
			$sql .= " WHERE schedule_id = '$scheduleId'";
			$query = $db->query($sql);

			return 'updated';
		} catch (Exception $e) {
			return null;
		}
	}
}
