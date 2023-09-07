<?php 
class Manifest{
	private $mysqli;
	function __construct($conn){
		$this-> mysqli = $conn;
	}
	public function callflight() {
		$db = $this->mysqli->conn;
		$sql ="SELECT DISTINCT flight_no FROM flight ORDER BY id DESC";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
	public function cekmanifest($flight, $tgl) {
		$db = $this->mysqli->conn;
		$sql ="SELECT COUNT(awb_number) AS jumlah FROM manifest WHERE flight_no='$flight' AND tanggal='$tgl'";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
	public function getall($man_code) {
		$db = $this->mysqli->conn;
		$sql ="SELECT manifest.manifesst_id, manifest.awb_number, manifest.koli, manifest.weight, manifest.comodity, manifest.remarks, manifest.flight_no, manifest.tanggal, cargo.quantity, cargo.weight AS kilo, SUM(manifest.weight) AS total_berat, SUM(manifest.koli) AS total_koli FROM manifest
		INNER JOIN cargo on manifest.awb_number = cargo.smu 
		WHERE manifest.man_code='$man_code' 
		GROUP BY manifest.awb_number
		 ORDER BY manifest.manifesst_id desc";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
	public function getall_first($man_code) {
		$db = $this->mysqli->conn;
		$sql ="SELECT manifest.manifesst_id, manifest.awb_number, manifest.koli, manifest.weight, manifest.comodity, manifest.remarks, manifest.flight_no, manifest.tanggal, cargo.quantity, cargo.weight AS kilo, manifest.type, manifest.type_name FROM manifest
		INNER JOIN cargo on manifest.awb_number = cargo.smu 
		WHERE manifest.man_code='$man_code'
		ORDER BY manifest.manifesst_id ASC";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
	public function getdistinct($man_code) {
		$db = $this->mysqli->conn;
		$sql ="SELECT manifest.awb_number, manifest.koli, manifest.weight, manifest.comodity, manifest.remarks, manifest.flight_no, manifest.tanggal, cargo.quantity, manifest.creator, cargo.weight AS kilo , SUM(manifest.koli) AS koliman, SUM(manifest.weight) AS kiloman FROM manifest INNER JOIN cargo on manifest.awb_number = cargo.smu WHERE manifest.man_code='$man_code'";
		$query = $db->query($sql)->fetch_object() or die ($db->error);

		return($query);
	}
	public function insert($eskiel) {
		$db = $this->mysqli->conn;
		$sql ="INSERT INTO manifest(awb_number, koli, weight, comodity, remarks, flight_no, registration, aircraft_type, tanggal, creator) VALUES $eskiel";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
	// public function getallmanifest($flight, $tgl) {
	// 	$db = $this->mysqli->conn;
	// 	$sql ="SELECT * FROM manifest WHERE flight_no='$flight' AND tanggal='$tgl'";
	// 	$query = $db->query($sql) or die ($db->error);

	// 	return($query);
	// }
	public function getallmanifest($flight, $tgl) {
		$db = $this->mysqli->conn;
		$sql ="SELECT * FROM manifest WHERE flight_no='$flight' AND tanggal='$tgl'";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
	public function calltlc($flight) {
		$db = $this->mysqli->conn;
		$sql ="SELECT tlc FROM flight WHERE flight_no='$flight'";
		$query = $db->query($sql)->fetch_object()->tlc or die ($db->error);

		return($query);
	}
	public function calldate($tanggal) {
		$db = $this->mysqli->conn;
		$sql ="SELECT DISTINCT man_code, flight_no, tanggal, creator, tanggal FROM manifest WHERE tanggal LIKE '%$tanggal%'";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
	public function callregist($flight, $tanggal) {
		$db = $this->mysqli->conn;
		$sql ="SELECT DISTINCT registration FROM manifest WHERE tanggal='$tanggal' AND flight_no='$flight'";
		$query = $db->query($sql)->fetch_object()->registration or die ($db->error);

		return($query);
	}
	public function deletemanifest($code_manifest) {
		$db = $this->mysqli->conn;
		$sql ="DELETE FROM manifest WHERE man_code='$code_manifest'";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}

	public function searchlike($smu){
		$db = $this->mysqli->conn;
		$sql = "SELECT cargo.smu, cargo.quantity, cargo.weight, cargo.comodity, cargo.agent_name, SUM(manifest.koli) AS jkoli, sum(manifest.weight) AS jweight FROM cargo LEFT JOIN manifest ON cargo.smu =  manifest.awb_number WHERE cargo.smu LIKE '%$smu%' GROUP BY cargo.smu LIMIT 0,99";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
	public function maketable(){
		date_default_timezone_set('Asia/Jakarta');
		$time = date('ymdHi');
		$name = "data".$time;
		$db = $this->mysqli->conn;
		$sql  ="CREATE TABLE $name(
			kode  char(20),
			smu char(12),
			qty int,
			weight int,
			comodity char(50),
			shipper char(50),
			flight char(30),
			reg char(10),
			tanggal datetime,
			petugas char(50)
		)";
		$query = $db->query($sql) or die ($db->error);
		return($name);
	}
	public function sementara($value, $name){
		$db = $this->mysqli->conn;
		$sql = "INSERT INTO $name (kode, smu, qty, weight, comodity, shipper, flight, reg, tanggal, petugas) VALUES $value ";
		$query = $db->query($sql) or die ($db->error);

		if(!$query){
			return("gagal");
		}else{
			return("berhasil");
		}
	}
	public function cekdata($awb){
		$db = $this->mysqli->conn;
		$sql = "SELECT *, SUM(koli) AS sumkol, SUM(weight) AS sumweight FROM manifest WHERE awb_number = '$awb'";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
	public function masukan($value){
		$db = $this->mysqli->conn;
		$sql = "INSERT INTO manifest (man_code, awb_number, koli, weight, comodity, remarks, flight_no, registration, tanggal, creator, type, type_name) VALUES $value ";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
	public function cekcargo($awb){
		$db = $this->mysqli->conn;
		$sql = "SELECT *, SUM(quantity) AS qty, SUM(weight) AS kilo FROM cargo WHERE smu = '$awb'";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
	public function dlsRegular($code_manifest){
		$db = $this->mysqli->conn;
		$sql = "SELECT manifesst_id, CONCAT(type,'',type_name) AS dls, SUM(koli) AS total_koli, SUM(weight) AS total_berat, registration, comodity, flight_no, type FROM manifest WHERE man_code='$code_manifest' AND type <> 'bulk' GROUP BY dls ORDER BY manifesst_id DESC";
		$query = $db->query($sql) or die ($db->error);

		return $query;
	}
	public function dlsBulk($code_manifest){
		$db = $this->mysqli->conn;
		$sql = "SELECT manifesst_id, CONCAT(type,'',type_name) AS dls, SUM(koli) AS total_koli, SUM(weight) AS total_berat, registration, comodity, flight_no, type, registration, remarks
			FROM manifest
			WHERE man_code='$code_manifest' AND type = 'bulk'
			GROUP BY dls
			ORDER BY manifesst_id ASC";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
}
?>