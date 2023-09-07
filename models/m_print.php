<?php 
class Printdo{
	private $mysqli;
	function __construct($conn){
		$this-> mysqli = $conn;
	}
	public function panggil($awb) {
		$db = $this->mysqli->conn;
		$sql ="SELECT `cargo`.* FROM cargo WHERE smu='$awb'";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
	public function destination($flight) {
		$db = $this->mysqli->conn;
		$sql ="SELECT destination FROM flight WHERE flight_no='$flight'";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}public function session($session) {
		$db = $this->mysqli->conn;
		$sql ="SELECT * FROM cargo WHERE session='$session' ORDER BY id ASC";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
	public function joindata($session) {
		$db = $this->mysqli->conn;
		$sql ="SELECT payment.session_kasir, payment.njg, payment.id, payment.smu, payment.stimestamp, cargo.no_do, cargo.flight_no, cargo.agent_name, cargo.shipper_name, payment.admin, payment.sewa_gudang, payment.kade, payment.pjkp2u, payment.airport_tax, payment.ppn, payment.materai, payment.total, cargo.weight, cargo.pic, cargo.status, payment.proses_by, cargo.last_editor FROM payment INNER JOIN cargo ON payment.smu=cargo.smu WHERE session_kasir='$session'";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
}