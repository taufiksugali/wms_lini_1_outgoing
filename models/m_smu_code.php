<?php 
class Smucode{
	private $mysqli;
	function __construct($conn){
		$this->mysqli = $conn;
	}

	public function all_code()
	{
		$db = $this->mysqli->conn;
		$sql ="SELECT `smu_code`.*,`airlines`.`airline_name` FROM `smu_code` JOIN `airlines` ON `airlines`.`airline_id` = `smu_code`.`airline_id` ";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}

	public function add_new_code($data)
	{
		$db = $this->mysqli->conn;
		$sql ="INSERT INTO `smu_code` (code, airline_id) VALUES ('".$data['code']."','".$data['airline_id']."')";
		$query = $db->query($sql);

		return($query);
	}

	public function get_cargo_classes()
	{
			$db = $this->mysqli->conn;
			$sql = "SELECT `cargo_class`.`class_id`,
			`cargo_class`.`type_id`,
			`cargo_class`.`class_code`,
			`cargo_class`.`general_name`,
			`cargo_type`.`type`
			FROM `cargo_class`
			JOIN `cargo_type` ON `cargo_type`.`cargo_type_id` = `cargo_class`.`type_id`";
			$query = $db->query($sql);

			return($query);
	}
}