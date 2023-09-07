<?php 
class Data_ap2{
	private $mysqli;
	function __construct($conn){
		$this-> mysqli = $conn;
	}

	public function insert_data($data)
	{

		$db = $this->mysqli->conn;
		$sql ="INSERT INTO data_ap2 (payment_id, push_status, create_by, api_user, api_key, no_invoice, tanggal, smu, kdairline, flight_number, dom_int, inc_out, asal, tujuan, jenis_kargo, tarif_kargo, koli, berat, volume, jml_hari, cargo_chg, kade, total_pendapatan_tanpa_ppn, total_pendapatan_dengan_ppn, pjt_handling_fee, rush_handling_fee, rush_service_fee, transhipment_fee, administration_fee, documents_fee, pecah_pu_fee, cool_cold_storage_fee, strong_room_fee, ac_room_fee, dg_room_fee, avi_room_fee, dangerous_good_check_fee, discount_fee, rksp_fee, hawb, hawb_fee, hawb_mawb_fee, csc_fee, envirotainer_elec_fee, aditional_costs, nawb_fee, barcode_fee, cargo_development_fee, dutiable_shipment_fee, fhl_fee, fwb_fee, cargo_inspection_report_fee, materai_fee, ppn_fee) 
		VALUES ( 
				'".$data['PAYMENT_ID']."',
				'".$data['PUSH_STATUS']."',
				'".$data['CREATE_BY']."',
				'".$data['USR']."',
				'".$data['PSW']."',
				'".$data['NO_INVOICE']."',
				'".$data['TANGGAL']."',
				'".$data['SMU']."',
				'".$data['KDAIRLINE']."',
				'".$data['FLIGHT_NUMBER']."',
				'".$data['DOM_INT']."',
				'".$data['INC_OUT']."',
				'".$data['ASAL']."',
				'".$data['TUJUAN']."',
				'".$data['JENIS_KARGO']."',
				'".$data['TARIF_KARGO']."',
				'".$data['KOLI']."',
				'".$data['BERAT']."',
				'".$data['VOLUME']."',
				'".$data['JML_HARI']."',
				'".$data['CARGO_CHG']."',
				'".$data['KADE']."',
				'".$data['TOTAL_PENDAPATAN_TANPA_PPN']."',
				'".$data['TOTAL_PENDAPATAN_DENGAN_PPN']."',
				'".$data['PJT_HANDLING_FEE']."',
				'".$data['RUSH_HANDLING_FEE']."',
				'".$data['RUSH_SERVICE_FEE']."',
				'".$data['TRANSHIPMENT_FEE']."',
				'".$data['ADMINISTRATION_FEE']."',
				'".$data['DOCUMENTS_FEE']."',
				'".$data['PECAH_PU_FEE']."',
				'".$data['COOL_COLD_STORAGE_FEE']."',
				'".$data['STRONG_ROOM_FEE']."',
				'".$data['AC_ROOM_FEE']."',
				'".$data['DG_ROOM_FEE']."',
				'".$data['AVI_ROOM_FEE']."',
				'".$data['DANGEROUS_GOOD_CHECK_FEE']."',
				'".$data['DISCOUNT_FEE']."',
				'".$data['RKSP_FEE']."',
				'".$data['HAWB']."',
				'".$data['HAWB_FEE']."',
				'".$data['HAWB_MAWB_FEE']."',
				'".$data['CSC_FEE']."',
				'".$data['ENVIROTAINER_ELEC_FEE']."',
				'".$data['ADDITIONAL_COSTS']."',
				'".$data['NAWB_FEE']."',
				'".$data['BARCODE_FEE']."',
				'".$data['CARGO_DEVELOPMENT_FEE']."',
				'".$data['DUTIABLE_SHIPMENT_FEE']."',
				'".$data['FHL_FEE']."',
				'".$data['FWB_FEE']."',
				'".$data['CARGO_INSPECTION_REPORT_FEE']."',
				'".$data['MATERAI_FEE']."',
				'".$data['PPN_FEE']."'
				)";
		$db->query($sql) or die ($db->error);
	}

	public function update_void($paymentId)
	{
		$db = $this->mysqli->conn;
		$sql ="UPDATE `data_ap2` SET `data_ap2`.`push_status` = 'void' WHERE `data_ap2`.`payment_id` = '$paymentId' ";
		$db->query($sql) or die ($db->error);
	}

	public function update_void_gagal($paymentId)
	{
		$db = $this->mysqli->conn;
		$sql ="UPDATE `data_ap2` SET `data_ap2`.`push_status` = 'void gagal' WHERE `data_ap2`.`payment_id` = '$paymentId' ";
		$db->query($sql) or die ($db->error);
	}

	public function select_by_payment_id($paymentId)
	{
		$db = $this->mysqli->conn;
		$sql ="SELECT `tanggal`, `no_invoice` FROM data_ap2 where payment_id = '$paymentId'
				";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
}
?>