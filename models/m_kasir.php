<?php
class Kasir
{
	private $mysqli;
	function __construct($conn)
	{
		$this->mysqli = $conn;
	}
	public function session()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM session_kasir";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function insert($values)
	{
		$db = $this->mysqli->conn;
		$sql = "INSERT INTO payment (njg, btb, smu, tanggal, admin, sewa_gudang, kade, pjkp2u, airport_tax, ppn, materai, total, proses_by, session_kasir, pricelist_id, npwp) VALUES $values";
		$db->query($sql) or die($db->error);
	}

	public function insert2($values)
	{
		try {
			$db = $this->mysqli->conn;
			$sql = "INSERT INTO payment (njg, btb, smu, tanggal, admin, sewa_gudang, kade, pjkp2u, airport_tax, ppn, materai, total, proses_by, session_kasir, pricelist_id, npwp, stimestamp) VALUES $values";
			$db->query($sql);
			if ($db->error) {
				throw new Exception($db->error);
			}

			$db = $this->mysqli->conn;
			$sql = "SELECT * FROM payment ORDER BY id DESC LIMIT 1";
			$query = $db->query($sql)->fetch_object() or die($db->error);

			return ($query);
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function create($pharsing)
	{
		$db = $this->mysqli->conn;
		$sql = "UPDATE session_kasir SET running='yes', pharsing ='$pharsing' WHERE id='1'";
		$db->query($sql) or die($db->error);
	}
	public function updatestat($smu)
	{
		$db = $this->mysqli->conn;
		$sql = "UPDATE cargo SET status='complete' WHERE smu='$smu'";
		$db->query($sql) or die($db->error);
	}
	public function end()
	{
		$db = $this->mysqli->conn;
		$sql = "UPDATE session_kasir SET running='no' WHERE id='1'";
		$db->query($sql) or die($db->error);
	}
	public function distagent()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT agent_name FROM cargo WHERE status BETWEEN 'proced' AND 'revisi' GROUP BY `agent_name` ORDER BY id ASC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function calnjg()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM payment ORDER BY id DESC LIMIT 1";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function distshipper($agent)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT DISTINCT shipper_name FROM cargo WHERE agent_name='$agent' AND status BETWEEN 'proced' AND 'revisi' ORDER BY id ASC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function calall($agent, $shipper)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM cargo WHERE shipper_name='$shipper' AND agent_name='$agent' AND status BETWEEN 'proced' AND 'revisi' ORDER BY id ASC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function calall_by_airline($agent, $shipper, $airline_id)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * 
		FROM cargo 
		JOIN `smu_code` ON `smu_code`.`code` = `cargo`.`smu_code`
		WHERE shipper_name='$shipper' 
		AND cargo.agent_name='$agent'
		AND smu_code.airline_id = '$airline_id'
		AND cargo.status BETWEEN 'proced' AND 'revisi' ORDER BY id ASC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function countsmu($agent, $shipper)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT COUNT(smu) AS jumlah FROM cargo WHERE shipper_name='$shipper' AND agent_name='$agent' AND status BETWEEN 'proced' AND 'revisi'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function sumqty($agent, $shipper)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT SUM(quantity) AS jumlah FROM cargo WHERE shipper_name='$shipper' AND agent_name='$agent' AND status BETWEEN 'proced' AND 'revisi'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function sumweight($agent, $shipper)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT SUM(weight) AS jumlah FROM cargo WHERE shipper_name='$shipper' AND agent_name='$agent' AND status BETWEEN 'proced' AND 'revisi'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function sumvol($agent, $shipper)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT SUM(volume) AS jumlah FROM cargo WHERE shipper_name='$shipper' AND agent_name='$agent' AND status BETWEEN 'proced' AND 'revisi'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function calprice()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM pricelist WHERE ongoing='yes'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function calltarget($agent, $shipper, $smu)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT `cargo`.*,
		`regulated_agents`.`ra_name`,
		`smu_code`,`airline_id`
		FROM cargo
		LEFT JOIN `regulated_agents` ON `regulated_agents`.`ra_id` = `cargo`.`ra_id`
		LEFT JOIN `smu_code` ON `smu_code`.`code` = `cargo`.`smu_code`
		WHERE shipper_name='$shipper' AND agent_name='$agent' AND smu='$smu' ORDER BY id ASC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function callflight($flight)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM flight WHERE flight_no='$flight'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	// terbilang
	public function penyebut($nilai)
	{
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " " . $huruf[$nilai];
		} else if ($nilai < 20) {
			$temp = penyebut($nilai - 10) . " belas";
		} else if ($nilai < 100) {
			$temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
		}
		return $temp;
	}

	public function terbilang($nilai)
	{
		if ($nilai < 0) {
			$hasil = "minus " . trim($nilai);
		} else {
			$hasil = trim($nilai);
		}
		return $hasil;
	}

	public function databyses($session)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT * FROM payment WHERE session_kasir ='$session'ORDER BY id ASC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function distses()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT DISTINCT session_kasir, proses_by FROM payment ORDER BY id DESC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function joindata($session)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT payment.id as payment_id, payment.session_kasir, payment.npwp, payment.id, payment.smu, payment.njg, cargo.no_do, cargo.agent_name, cargo.shipper_name, payment.admin, payment.sewa_gudang, payment.kade, payment.pjkp2u, payment.airport_tax, payment.ppn, payment.materai, payment.total, cargo.pic, cargo.status, payment.proses_by, payment.stimestamp AS tanggalan, cargo.last_editor, regulated_agents.ra_name, payment.payment_status, payment.void_by
		FROM payment 
		INNER JOIN cargo ON payment.smu=cargo.smu 
		LEFT JOIN regulated_agents ON regulated_agents.ra_id = cargo.ra_id
		WHERE session_kasir='$session' ORDER BY payment.njg ASC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function joindata_airline($session, $airline)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT payment.id as payment_id, payment.session_kasir, payment.id, payment.smu, payment.njg, cargo.no_do, cargo.agent_name, cargo.shipper_name, payment.admin, 			payment.sewa_gudang, payment.kade, payment.pjkp2u, payment.airport_tax, payment.ppn, payment.materai, payment.total, cargo.pic, 			cargo.status, payment.proses_by, payment.stimestamp AS tanggalan, cargo.last_editor, payment.payment_status, payment.void_by 
		FROM payment 
		INNER JOIN cargo ON payment.smu=cargo.smu 
		JOIN `smu_code` ON `smu_code`.`code` = `cargo`.`smu_code`
		JOIN `airlines` ON `airlines`.`airline_id` = `smu_code`.`airline_id`
		LEFT JOIN regulated_agents ON regulated_agents.ra_id = cargo.ra_id
		WHERE session_kasir='$session'
		AND `airlines`.`airline_name` = '$airline'
		ORDER BY payment.njg ASC";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function joindata_print($smu)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT payment.njg, cargo.agent_name, cargo.shipper_name, cargo.pic, cargo.tanggal, payment.smu, cargo.no_do, flight.tlc, cargo.quantity, cargo.weight, cargo.volume, payment.sewa_gudang, payment.admin, payment.kade, payment.pjkp2u, payment.airport_tax, payment.ppn, payment.materai, payment.total, payment.proses_by, payment.stimestamp, regulated_agents.ra_name, pricelist.admin as p_admin, pricelist.sg as p_sg, pricelist.kade as p_kade, pricelist.pjkp2u as p_pjkp2u, pricelist.materai as p_materai, pricelist.airport_surcharge as p_airport_surcharge, payment.npwp
		FROM payment 
		INNER JOIN cargo ON payment.smu=cargo.smu 
		INNER JOIN  flight ON flight.flight_no=cargo.flight_no  
		LEFT JOIN regulated_agents ON regulated_agents.ra_id = cargo.ra_id
		JOIN pricelist on pricelist.pricelist_id = payment.pricelist_id
		WHERE payment.smu='$smu'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function callidfromcargo($smu)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT id FROM cargo WHERE smu ='$smu'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function voidsmu($id, $smu, $keterangan)
	{
		$db = $this->mysqli->conn;
		$sql = "UPDATE payment SET smu = '$smu', keterangan='$keterangan' WHERE id='$id'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}
	public function voidsmutocargo($id, $smu, $status, $petugas)
	{
		$db = $this->mysqli->conn;
		$sql = "UPDATE cargo SET smu = '$smu', status='$status', last_editor='$petugas' WHERE id='$id'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function all_by_session($session, $status)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT cargo.smu, cargo.no_do AS no_btb, cargo.tanggal AS tanggalbtb, payment.njg, payment.stimestamp AS tanggalnjg, cargo.agent_name, cargo.shipper_name, cargo.pic, cargo.flight_no, flight.tlc, cargo.comodity, cargo.quantity, cargo.weight, cargo.volume, payment.admin, payment.sewa_gudang, payment.kade, payment.pjkp2u, payment.airport_tax, payment.ppn, payment.materai, payment.total, cargo.session, cargo.proses_by  AS proses_btb, payment.session_kasir, payment.npwp, payment.proses_by AS proses_njg, payment.keterangan FROM payment INNER JOIN cargo ON cargo.smu=payment.smu LEFT JOIN flight ON cargo.flight_no=flight.flight_no WHERE cargo.status='$status' AND payment.session_kasir = '$session'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function getSessionData($session)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT 
				cargo.smu,
				cargo.no_do AS no_btb,
				cargo.tanggal AS tanggalbtb,
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
				payment.npwp,
				payment.proses_by AS proses_njg,
				payment.keterangan,
				payment.payment_status,
				payment.void_by,
				cargo.last_editor
			FROM payment
			JOIN cargo ON cargo.no_do = payment.btb
			JOIN flight on flight.flight_no = cargo.flight_no
			WHERE payment.session_kasir = '$session'";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function payment_id($id)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT `njg` FROM payment where id = '$id'
		";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function last_id()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT `id` FROM payment 
		ORDER BY `id` DESC
		LIMIT 1
		";
		$query = $db->query($sql) or die($db->error);

		return ($query->fetch_object());
	}

	public function get_distinct_payment()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT 
		`cargo`.`agent_name`,
		`cargo`.`shipper_name`,
		COUNT(`cargo`.`smu`) AS `smu`,
		SUM(`cargo`.`quantity`) AS `quantity`,
		SUM(`cargo`.`weight`) AS `weight`,
		SUM(`cargo`.`volume`) AS `volume`
		FROM `cargo`
		WHERE `status` BETWEEN 'proced' AND 'revisi' 
		GROUP BY `cargo`.`agent_name`, `cargo`.`shipper_name`
		ORDER BY `cargo`.`id` ASC
		";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function get_distinct_payment_by_airline($airline_id)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT 
		`cargo`.`agent_name`,
		`cargo`.`shipper_name`,
		COUNT(`cargo`.`smu`) AS `smu`,
		SUM(`cargo`.`quantity`) AS `quantity`,
		SUM(`cargo`.`weight`) AS `weight`,
		SUM(`cargo`.`volume`) AS `volume`
		FROM `cargo`
		JOIN `smu_code` ON `smu_code`.`code` = `cargo`.`smu_code`
		WHERE `smu_code`.`airline_id` = '$airline_id'
		AND (`status` BETWEEN 'proced' AND 'revisi')
		GROUP BY `cargo`.`agent_name`, `cargo`.`shipper_name`
		ORDER BY `cargo`.`id` ASC
		";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function get_all_airline()
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT *
		FROM `airlines`
		";
		$query = $db->query($sql) or die($db->error);

		return ($query);
	}

	public function getAgentByName($name)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT *
			FROM `agent`
			WHERE`agent_name` = '$name'
			ORDER BY `agent_npwp` DESC
			LIMIT 1";

		$query = $db->query($sql)->fetch_object() or die($db->error);

		return ($query);
	}



	public function getInvoiceById($invoiceId)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT *, payment.id as payment_id, cargo.id as cargo_id
				FROM `payment`
				JOIN `cargo` ON `cargo`.`no_do` = `payment`.`btb`
				JOIN `smu_code` ON `smu_code`.`code` = `cargo`.`smu_code`
				JOIN `flight` ON `flight`.`flight_no` = `cargo`.`flight_no` AND `flight`.`airline_id` = `smu_code`.`airline_id`
				WHERE `payment`.`id` = '$invoiceId'";
		$query = $db->query($sql)->fetch_object() or die($db->error);
		return ($query);
	}

	public function getInvoiceByIds($invoiceIds)
	{
		$db = $this->mysqli->conn;
		$sql = "SELECT 
					payment.id as payment_id,
					payment.njg,
					payment.admin as payment_admin,
					payment.sewa_gudang as payment_sg,
					payment.kade as payment_kade,
					payment.pjkp2u as payment_pjkp2u,
					payment.ppn as payment_ppn,
					payment.materai as payment_materai,
					payment.airport_tax as payment_airtax,
					payment.total as payment_total,
					payment.proses_by as payment_cashier,
					payment.session_kasir,
					payment.tanggal as payment_date,
					payment.stimestamp as payment_timestamp,
					payment.npwp,
					pricelist.admin as pl_admin,
					pricelist.sg as pl_sg,
					pricelist.kade as pl_kade,
					pricelist.pjkp2u as pl_pjkp2u,
					pricelist.airport_surcharge as pl_airtax,
					pricelist.materai as pl_materai,
					cargo.smu,
					cargo.no_do,
					cargo.shipment_type,
					cargo.flight_no,
					cargo.shipment_type,
					cargo.comodity,
					cargo.agent_name,
					cargo.shipper_name,
					cargo.pic,
					cargo.quantity,
					cargo.weight,
					cargo.volume,
					cargo.tanggal as btb_date,
					cargo.proses_by as btb_user,
					cargo.session as btb_session,
					cargo.id as cargo_id,
					flight.tlc,
					ra.ra_name
				FROM `payment`
				JOIN `cargo` ON `cargo`.`no_do` = `payment`.`btb`
				JOIN `smu_code` ON `smu_code`.`code` = `cargo`.`smu_code`
				JOIN `flight` ON `flight`.`flight_no` = `cargo`.`flight_no` AND `flight`.`airline_id` = `smu_code`.`airline_id`
				JOIN `pricelist` ON `pricelist`.`pricelist_id` = `payment`.`pricelist_id`
				LEFT JOIN `regulated_agents` ra ON ra.`ra_id` = `cargo`.`ra_id`
				WHERE `payment`.`id` IN $invoiceIds";
		$query = $db->query($sql) or die($db->error);
		return ($query);
	}

	public function updateData($paymentId, $data)
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
			$sql = "UPDATE payment SET ";
			$sql .= $string;
			$sql .= " WHERE id = '$paymentId'";
			$query = $db->query($sql);

			return 'updated';
		} catch (Exception $e) {
			return null;
		}
	}
}
