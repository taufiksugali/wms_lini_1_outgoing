<?php 
class Airline{
	private $mysqli;
	function __construct($conn){
		$this->mysqli = $conn;
	}

	public function call_all_airline()
	{	
		$db = $this->mysqli->conn;
		$sql = 'SELECT * FROM airlines GROUP BY airline_name';
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}

	public function add_new_airline($data)
	{	
		$db = $this->mysqli->conn;
		$sql = 'INSERT INTO `airlines` (airline_id, airline_name) VALUES ("'.$data['airline_id'].'","'.$data['airline_name'].'")';
		$query = $db->query($sql);

		return($query);
	}

	public function call_all_airline_ungroup()
	{	
		$db = $this->mysqli->conn;
		$sql = 'SELECT * FROM airlines';
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
}