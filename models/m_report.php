<?php
class Report
{
	private $mysqli;
	function __construct($conn)
	{
		$this->mysqli = $conn;
	}
	public function allbydate($date1, $date2, $status)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT cargo.smu,
		cargo.no_do,
		cargo.ctimestamp AS tanggalbtb,
		payment.njg,
		payment.stimestamp AS tanggalnjg,
		cargo.agent_name,
		cargo.shipper_name,
		cargo.pic,
		cargo.flight_no,
		flight.tlc,
		cargo.comodity,
		cargo.quantity,
		cargo.weight,
		cargo.volume,
		payment.admin,
		payment.sewa_gudang,
		payment.kade,
		payment.pjkp2u,
		payment.airport_tax,
		payment.ppn,
		payment.materai,
		payment.total,
		cargo.session,
		cargo.proses_by  AS proses_btb,
		payment.session_kasir,
		payment.proses_by AS proses_njg,
		payment.keterangan ,
		payment.npwp,
		payment.payment_status,
		payment.void_by
		FROM payment 
		INNER JOIN cargo ON cargo.smu=payment.smu 
		LEFT JOIN flight ON cargo.flight_no=flight.flight_no 
		WHERE cargo.status='$status' 
		AND payment.stimestamp BETWEEN '$date1' AND '$date2'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function allbydate2($date1, $date2)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT cargo.smu,
		cargo.no_do,
		cargo.ctimestamp AS tanggalbtb,
		payment.njg,
		payment.stimestamp AS tanggalnjg,
		cargo.agent_name,
		cargo.shipper_name,
		cargo.pic,
		cargo.flight_no,
		flight.tlc,
		cargo.comodity,
		cargo.quantity,
		cargo.weight,
		cargo.volume,
		payment.admin,
		payment.sewa_gudang,
		payment.kade,
		payment.pjkp2u,
		payment.airport_tax,
		payment.ppn,
		payment.materai,
		payment.total,
		cargo.session,
		cargo.proses_by  AS proses_btb,
		payment.session_kasir,
		payment.proses_by AS proses_njg,
		payment.keterangan ,
		payment.npwp,
		payment.payment_status,
		payment.void_by
		FROM payment 
		INNER JOIN cargo ON cargo.smu=payment.smu 
		LEFT JOIN flight ON cargo.flight_no=flight.flight_no 
		WHERE payment.stimestamp BETWEEN '$date1' AND '$date2'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function allbydate_airline($date1, $date2, $status, $airline)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT cargo.smu,
			cargo.no_do,
			cargo.ctimestamp AS tanggalbtb,
			payment.njg,
			payment.stimestamp AS tanggalnjg,
			cargo.agent_name,
			cargo.shipper_name,
			cargo.pic,
			cargo.flight_no,
			flight.tlc,
			cargo.comodity,
			cargo.quantity,
			cargo.weight,
			cargo.volume,
			payment.admin,
			payment.sewa_gudang,
			payment.kade,
			payment.pjkp2u,
			payment.airport_tax,
			payment.ppn,
			payment.materai,
			payment.total,
			cargo.session,
			cargo.proses_by  AS proses_btb,
			payment.session_kasir,
			payment.proses_by AS proses_njg,
			payment.keterangan,
			payment.npwp,
			payment.payment_status,
			payment.void_by
		FROM payment 
		INNER JOIN cargo ON cargo.smu=payment.smu 
		LEFT JOIN flight ON cargo.flight_no=flight.flight_no
		JOIN `smu_code` ON `smu_code`.`code` = `cargo`.`smu_code`
		JOIN `airlines` ON `airlines`.`airline_id` = `smu_code`.`airline_id`
		WHERE cargo.status='$status' 
		AND `airlines`.`airline_name` = '$airline'
		AND payment.stimestamp BETWEEN '$date1' AND '$date2'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function allbydate_airline2($date1, $date2, $airline)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT cargo.smu,
			cargo.no_do,
			cargo.ctimestamp AS tanggalbtb,
			payment.njg,
			payment.stimestamp AS tanggalnjg,
			cargo.agent_name,
			cargo.shipper_name,
			cargo.pic,
			cargo.flight_no,
			flight.tlc,
			cargo.comodity,
			cargo.quantity,
			cargo.weight,
			cargo.volume,
			payment.admin,
			payment.sewa_gudang,
			payment.kade,
			payment.pjkp2u,
			payment.airport_tax,
			payment.ppn,
			payment.materai,
			payment.total,
			cargo.session,
			cargo.proses_by  AS proses_btb,
			payment.session_kasir,
			payment.proses_by AS proses_njg,
			payment.keterangan,
			payment.npwp,
			payment.payment_status,
			payment.void_by
		FROM payment 
		INNER JOIN cargo ON cargo.smu=payment.smu 
		LEFT JOIN flight ON cargo.flight_no=flight.flight_no
		JOIN `smu_code` ON `smu_code`.`code` = `cargo`.`smu_code`
		JOIN `airlines` ON `airlines`.`airline_id` = `smu_code`.`airline_id`
		WHERE `airlines`.`airline_name` = '$airline'
		AND payment.stimestamp BETWEEN '$date1' AND '$date2'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function allbymonth($date)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT proses_by,
		session_kasir,
		SUM(total) AS uang
		FROM payment WHERE tanggal LIKE '$date%' AND stimestamp BETWEEN '$date 00:00:00' AND '$date 23:59:59'
		GROUP BY session_kasir
		ORDER BY id ASC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function allbymonth_ses($date)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT proses_by,
		session_kasir,
		SUM(total) AS uang
		FROM payment WHERE session_kasir LIKE '%$date'
		GROUP BY session_kasir
		ORDER BY id ASC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function updatePaymentTime($id, $time)
	{
		$db = $this->mysqli->conn;
		$sql = "UPDATE payment SET payment.stimestamp = '$time'
		WHERE payment.id = '$id'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
}
