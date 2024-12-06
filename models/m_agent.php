<?php
class Agent
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

	public function getAllAgent()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM `agent`
		ORDER BY `agent`.`agent_name` ASC";
		$query = $db->query($sql);

		return $query;
	}

	public function insertAgent($data)
	{
		try {
			$db = $this->mysqli->conn;
			$sql = "SELECT * FROM `agent` WHERE agent_name = '" . $data['agent_name'] . "'";
			$query = $db->query($sql);
			if ($query->num_rows > 0) {
				throw new Exception('Agent name already exist');
			}

			$db = $this->mysqli->conn;
			$sql = "INSERT INTO `agent` (agent_name, agent_npwp, agent_address) VALUES ('" . $data['agent_name'] . "', '" . $data['agent_npwp'] . "', '" . $data['agent_address'] . "')";
			$query = $db->query($sql);

			return 'inserted';
		} catch (Exception $e) {
			return $e;
		}
	}

	public function updateAgent($agent_id, $data)
	{
		try {
			$db = $this->mysqli->conn;
			$sql = "SELECT * FROM `agent` WHERE agent_name = '" . $data['agent_name'] . "'";
			$query = $db->query($sql);
			if ($query->num_rows > 0) {
				if ($query->num_rows > 1) {
					throw new Exception('Agent name already exist');
				} else {
					$result = $query->fetch_object();
					if ($result->agent_id != $agent_id) {
						throw new Exception('Agent name already exist');
					}
				}
			}
			$db = $this->mysqli->conn;
			$sql = "UPDATE `agent` SET agent_name = '" . $data['agent_name'] . "', agent_npwp = '" . $data['agent_npwp'] . "', agent_address = '" . $data['agent_address'] . "' WHERE agent_id = '" . $agent_id . "'";
			$query = $db->query($sql);
			return 'updated';
		} catch (Exception $e) {
			return $e;
		}
	}

	public function deleteAgent($agent_id)
	{
		try {
			$db = $this->mysqli->conn;
			$sql = "DELETE FROM `agent` WHERE agent_id = '" . $agent_id . "'";
			$query = $db->query($sql);

			return 'deleted';
		} catch (Exception $e) {
			return $e;
		}
	}
}
