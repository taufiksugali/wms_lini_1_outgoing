<?php
class Btb
{
	private $mysqli;
	function __construct($conn)
	{
		$this->mysqli = $conn;
	}
	public function session()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM session_btb";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function create($pharsing)
	{
		$db = $this->mysqli->conn;
		$sql = "UPDATE session_btb SET running='yes', pharsing ='$pharsing' WHERE id='1'";
		$db->query($sql) or die($db->error);
	}
	public function end()
	{
		$db = $this->mysqli->conn;
		$sql = "UPDATE session_btb SET running='no' WHERE id='1'";
		$db->query($sql) or die($db->error);
	}
	public function insert($data_input)
	{
		$db = $this->mysqli->conn;
		$sql = "INSERT INTO cargo (smu_code, smu, no_do, flight_no, shipment_type, comodity, agent_name, shipper_name, pic, quantity, weight, volume, tanggal, status, proses_by, session, ra_id) VALUES ($data_input)";

		$query = $db->query($sql) or die($db->error);
		if (!$query) {
			return false;
		} else {
			return true;
		}
	}
	public function donum()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT no_do FROM cargo ORDER BY id DESC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function callflight()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT flight_no FROM flight";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function insertflight($airline_id, $flight, $destination, $tlc)
	{
		$db = $this->mysqli->conn;
		$sql = "INSERT INTO flight (airline_id, flight_no, destination,tlc) VALUES ('$airline_id','$flight','$destination','$tlc')";
		$query = $db->query($sql);

		return ($query);
	}
	public function updateflight($id, $flight, $destination, $tlc)
	{
		$db = $this->mysqli->conn;
		$sql = "UPDATE flight SET flight_no='$flight', destination='$destination', tlc='$tlc' WHERE id='$id'";
		$query = $db->query($sql);

		return ($query);
	}
	public function callflightall()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM flight";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function distses()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT DISTINCT session FROM cargo ORDER BY id DESC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function cargobyses($session)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * 
		FROM cargo
		LEFT JOIN regulated_agents ON regulated_agents.ra_id = cargo.ra_id
		WHERE session ='$session'ORDER BY id ASC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function cargobysesairline($session, $airline)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * 
		FROM cargo 
		JOIN `smu_code` ON `smu_code`.`code` = `cargo`.`smu_code`
		JOIN `airlines` ON `airlines`.`airline_id` = `smu_code`.`airline_id`
		LEFT JOIN regulated_agents ON regulated_agents.ra_id = cargo.ra_idLEFT JOIN regulated_agents ON regulated_agents.ra_id = cargo.ra_id
		WHERE `cargo`.`session` ='$session'
		AND `airlines`.`airline_name` = '$airline'
		ORDER BY id ASC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function cargo_by_airline($session, $airline)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT `cargo`.*, `regulated_agents`.*
		FROM cargo 
		JOIN `smu_code` ON `smu_code`.`code` = `cargo`.`smu_code`
		JOIN `airlines` ON `airlines`.`airline_id` = `smu_code`.`airline_id`
		LEFT JOIN regulated_agents ON regulated_agents.ra_id = cargo.ra_id
		WHERE `cargo`.`session` ='$session' AND `airlines`.`airline_name` = '$airline'
		ORDER BY id ASC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function cargobysmu($smu)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM cargo WHERE smu ='$smu'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function cargobyid($id)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM cargo WHERE id ='$id'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function updatecgo($data, $id)
	{
		$db = $this->mysqli->conn;
		$sql = "UPDATE cargo SET $data WHERE id = $id";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function updatecgoccl($smu)
	{
		$db = $this->mysqli->conn;
		$sql = "UPDATE cargo SET status='cancel' WHERE cargo.smu ='$smu'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function deleteflight($id)
	{
		$db = $this->mysqli->conn;
		$sql = "DELETE FROM flight WHERE id='$id'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function carismu()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT smu 
		FROM cargo 
		LEFT JOIN regulated_agents ON regulated_agents.ra_id = cargo.ra_id
		ORDER BY id DESC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function cariBtb($nomor)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT no_do FROM cargo WHERE no_do = '$nomor'";
		$query = $db->query($sql) or die($db->error);


		return ($query);
	}


	public function carialldata($smu)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT cargo.status, cargo.smu, cargo.agent_name, cargo.shipper_name, cargo.pic, cargo.quantity, cargo.weight, cargo.volume, cargo.flight_no, flight.destination, cargo.ctimestamp, cargo.no_do, cargo.session, cargo.proses_by, manifest.flight_no AS man_flight, manifest.timestamp AS man_date, manifest.creator, payment.stimestamp, payment.session_kasir, payment.proses_by AS proses_kasir, payment.njg, payment.admin, payment.sewa_gudang, payment.kade, payment.pjkp2u, payment.airport_tax, payment.ppn, payment.materai, payment.total, regulated_agents.ra_name
		FROM cargo 
		LEFT JOIN manifest ON cargo.smu = manifest.awb_number 
		LEFT JOIN payment ON  cargo.smu=payment.smu 
		LEFT JOIN flight ON cargo.flight_no =flight.flight_no 
		LEFT JOIN regulated_agents ON regulated_agents.ra_id = cargo.ra_id
		WHERE cargo.smu = '$smu' ";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function cariflight($tlc)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT flight_no FROM flight WHERE tlc = '$tlc' ORDER BY id DESC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function cariTlc($flight)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT tlc FROM flight WHERE flight_no = '$flight'";
		$query = $db->query($sql) or die($db->error);


		return ($query);
	}


	public function check_awb($awb)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT COUNT(`cargo`.`smu`) AS total_row FROM `cargo` WHERE `cargo`.`smu` = '$awb'";
		$query = $db->query($sql) or die($db->error);
		$result = $query->fetch_object();

		if ($result->total_row > 0) {
			return false;
		} else {
			return true;
		}
	}

	public function get_all_agent()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM `agent`";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function add_agent_name($agent_name)
	{
		$db = $this->mysqli->conn;
		$sql = "INSERT INTO `agent` (agent_name) VALUES ('$agent_name')";
		$query = $db->query($sql);

		return ($query);
	}

	public function check_ra_name($ra_name)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM `regulated_agents` WHERE `regulated_agents`.`ra_name` = '$ra_name'";
		$query = $db->query($sql) or die($db->error);

		if ($query->num_rows > 0) {
			return $query->fetch_object();
		} else {
			return false;
		}
	}

	public function insert_new_regulated_agent($ra_name)
	{
		$db = $this->mysqli->conn;
		$sql = "INSERT INTO `regulated_agents` (ra_name) VALUES ('$ra_name')";
		$query = $db->query($sql);

		return ($query);
	}

	public function get_all_ra()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM `regulated_agents` WHERE `regulated_agents`.`ra_status` = '1'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	function updateNPWP($agent_id, $npwp)
	{
		$db = $this->mysqli->conn;
		$sql = "UPDATE `agent` SET agent_npwp='$npwp' WHERE agent_id = '$agent_id'";
		$db->query($sql) or die($db->error);
		return true;
	}
}
