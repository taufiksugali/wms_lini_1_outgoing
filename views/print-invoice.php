<?php
date_default_timezone_set("Asia/Bangkok");
session_start();
require_once('../config/config.php');
require_once('../models/database.php');
include('../models/m_kasir.php');
include('../models/m_data_ap2.php');
$connection = new Database($host, $user, $pass, $database);
$data = new Kasir($connection);
$data_ap = new Data_ap2($connection);
// call sesion kasir
$s_session = $data->session()->fetch_object();
$s_kasir = $s_session->pharsing;

// call pricelist
$pricelist= $data->calprice()->fetch_object();
$admin = $pricelist->admin;
$sg = $pricelist->sg;
$kade = $pricelist->kade;
$pjkp2u = $pricelist->pjkp2u;
$materai = $pricelist->materai;
$airport_surcharge = $pricelist->airport_surcharge;

if(isset($_POST['print_invoice'])){
	$agent = $_POST['d_agent'];
	$shipper = $_POST['d_shipper'];
	$d_smu = $_POST['d_smu'];
	// var_dump($d_smu); die();
	$d_smu = substr($d_smu,0,-1);
	$smu = explode(",", $d_smu);
	$count = count($smu) ;
	$text = '';
	$s_count = 1;
	foreach($smu as $key => $value){
		if($s_count == $count){
			$text = $text."'".$value."'";

		}else{
			$text = $text."'".$value."',";
		}
		$s_count++;
	}
}else{
	echo "<script>window.close()</script>";
}

// die();
function penyebut($nilai) {
	$nilai = abs($nilai);
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($nilai < 12) {
		$temp = " ". $huruf[$nilai];
	} else if ($nilai <20) {
		$temp = penyebut($nilai - 10). " belas";
	} else if ($nilai < 100) {
		$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
	}     
	return $temp;
}

?>	
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
	<link href="../assets/css/print_invoice.css" rel="stylesheet" crossorigin="anonymous">
	<title>Print Invoice</title>
</head>
<body>
	<div class="contener">
		<!-- php here -->
		<?php
		$values="";
		$d_njg = $data->calnjg();
		$njg1 = $d_njg->fetch_object();
		$njg = $njg1->njg;
		$new_njg = $njg;
		for ($i=0; $i <$count ; $i++) { 
			$new_njg = $new_njg + 1;
			$awb= $smu[$i];
			
			// panggil data cargo
			$panggil = $data->calltarget($agent, $shipper, $awb);
			$result = $panggil->fetch_object();

			$pic = $result->pic;
			$btb = $result->no_do;
			$flight = $result->flight_no;
			$date_btb = $result->tanggal;
			$quantity = $result->quantity;
			$weight = $result->weight;
			$volume = $result->volume;
			$comodity = $result->comodity;
			$payment_id = $result->id;
			$kdairline = $result->airline_id;

			// if(!$pic){
			// 	echo "<script>window.close()</script>";
			// }



			// if($weight <= $volume && $volume <= 10 ){
			// 	$net=10;
			// }elseif($weight < $volume){
			// 	$net = $volume;
			// }else{$net = $weight;}

			if($weight >= $volume && $weight > 10){
				$net = $weight;
			}elseif($volume > $weight && $volume > 10 ){
				$net = $volume;
			}else{
				$net = 10;
			}



			// panggil data flight
			$p_flight = $data->callflight($flight);
			$f_result = $p_flight->fetch_object();

			$destination = $f_result->destination;
			$des_tlc = $f_result->tlc;
			$tlc = $f_result->tlc;

			if($result->smu_code == '' || $result->smu_code == '273' || $result->smu_code == '181'){
				$send_flight = $f_result->flight_no;
			}else{
				$explodes = explode('-', $result->flight_no);
				$send_flight = $explodes[1];
			}

			// new data
			$tsg = $net * $sg;
			$tpjkp2u = $net===10? 10*$pjkp2u : $weight * $pjkp2u;
			$tkade = $net===10? 10*$kade : $weight * $kade;
			$tairport_surcharge = $net * $airport_surcharge;
			$tppn = round((($tsg + $tpjkp2u + $tkade + $admin + $tairport_surcharge)*11)/100);
			if(($tsg + $tpjkp2u + $tkade + $admin + $tairport_surcharge + $tppn) < 10000000){
				$tmaterai = 0;
			}else{$tmaterai=$materai;}
			$total = round($tsg + $tpjkp2u + $tkade + $admin + $tairport_surcharge + $tppn + $tmaterai);
			$date = date("Y-m-d");
			$name = $_SESSION['name'];

			$values.="('$new_njg','$btb','$awb','$date','$admin','$tsg','$tkade','$tpjkp2u','$tairport_surcharge','$tppn','$tmaterai','$total','$name','$s_kasir'),"
			?>
			<div class="content ">
				<div class="judul text-center position-relative pt-3">
					<h5>PT POS LOGISTIK INDONESIA</h5>
					<p>Terminal Kargo Bandara Soekarno-Hatta Jakarta 19100</p>
					<p>Telp. 021-5500278 Faks. 021-5500277</p>
					<div class="logo position-absolute start-0 top-0 pt-4">
						<img src="../assets/image/poslog.png" alt="poslog">
					</div>
				</div>
				<div class="data">
					<div class="d-flex justify-content-between">
						<div class="kolom d-flex">
							<div class="info me-2">
								No.NJG<br>
								Agent/SHP/PIC<br>
								AWB/BTB
							</div>
							<div >
								: <?php echo $new_njg; ?><br>
								: <?php echo $agent; ?>/<?php echo $shipper; ?>/<?php echo $pic; ?><br>
								: <?php echo $awb; ?>/<?php echo $btb; ?>
							</div>
						</div>
						<div class="kolom d-flex">
							<div class="info me-2">
								Tanggal BTB<br>
								Destination
							</div>
							<div >
								: <?php echo $date_btb; ?> <br>
								: <?php echo $tlc; ?>
							</div>
						</div>
					</div>
					<div class="d-flex justify-content-between px-4">
						<div>
							QTY: <?php echo $quantity; ?>
						</div>
						<div>
							Actual Weight: <?php echo $weight; ?>
						</div>
						<div>
							Volumetric Weight: <?php echo $volume; ?>
						</div>
						<div>
							Chargable Weight: <?php echo $net; ?>
						</div>
					</div>
				</div>
				<div class="rincian">
					<div class="d-flex justify-content-between px-4">
						<div>
							Jasa Gudang <br>
							Admin <br>
							JKP2U <br>
							Kade <br>
							Airport Surcharge <br>
							Pajak 11% <br>
							Materai <br>
							Total Bayar 
						</div>
						<div>
							: <?php echo $net; ?> X 1 X <?php echo $sg; ?> <br>
							<br>
							: <?php echo $weight; ?> X 1 X <?php echo $pjkp2u; ?> <br>
							: <?php echo $weight; ?> X 1 X <?php echo $kade; ?> <br>
							: <?php echo $net; ?> X 1 X <?php echo $airport_surcharge; ?> <br> 
						</div>
						<div>
							=Rp. <br>
							=Rp. <br>
							=Rp. <br>
							=Rp. <br>
							=Rp. <br>
							=Rp. <br>
							=Rp. <br>
							=Rp. <br>
						</div>
						<div class="text-end">
							<?php echo number_format($tsg); ?> <br>
							<?php echo number_format($admin); ?> <br>
							<?php echo number_format($tpjkp2u); ?> <br>
							<?php echo number_format($tkade); ?> <br>
							<?php echo number_format($tairport_surcharge); ?> <br>
							<?php echo number_format($tppn); ?> <br>
							<?php echo number_format($tmaterai); ?> <br>
							<?php echo number_format($total); ?> <br>
						</div>
					</div>
					<div class="terbilang">
						<?php 
						$terbilang = $data->terbilang($total);
						$penyebut = penyebut($terbilang);
						?>
						<i><strong>Terbilang : </strong><?php echo ucwords($penyebut)." Rupiah"; ?></i>
					</div>
				</div>
				<div class="footer">
					<div class="d-flex justify-content-end">
						Cengkareng, <?php echo date("d-m-Y", strtotime($date)); ?>
					</div>
					<div class="d-flex justify-content-between">
						<div>
							Metode bayar: CASH
						</div>
						<div>
							Process by: <?php echo $name; ?>
						</div>
					</div>					
				</div>
			</div>
			<?php 
			$data->updatestat($awb);
			$url = "https://apisigo.angkasapura2.co.id/api/invo_dtl_v2";

			$fields = array(
				'USR' => 'user.api.poslog',
				'PSW' => 'user.api.poslog',
				'NO_INVOICE' => $new_njg,
				'TANGGAL' => date('Y-m-d H:i:s'),
				'SMU' => $awb,
				'KDAIRLINE' => $kdairline,
				'FLIGHT_NUMBER' =>  $send_flight,
				'DOM_INT' => 'D',
				'INC_OUT' => 'O',
				'ASAL' => 'CGK',
				'TUJUAN' => $des_tlc,
				'JENIS_KARGO' => $comodity,
				'TARIF_KARGO' => '1',
				'KOLI' => $quantity,
				'BERAT' => $weight,
				'VOLUME' => $volume,
				'JML_HARI' => 1,
				'CARGO_CHG' => $tsg,
				'KADE' => $tkade,
				'TOTAL_PENDAPATAN_TANPA_PPN' => $total - $tppn,
				'TOTAL_PENDAPATAN_DENGAN_PPN' => $total,
				'PJT_HANDLING_FEE' => '0',
				'RUSH_HANDLING_FEE' => '0',
				'RUSH_SERVICE_FEE' => '0',
				'TRANSHIPMENT_FEE' => '0',
				'ADMINISTRATION_FEE' => $admin,
				'DOCUMENTS_FEE' => '0',
				'PECAH_PU_FEE' => '0',
				'COOL_COLD_STORAGE_FEE' => '0',
				'STRONG_ROOM_FEE' => '0',
				'AC_ROOM_FEE' => '0',
				'DG_ROOM_FEE' => '0',
				'AVI_ROOM_FEE' => '0',
				'DANGEROUS_GOOD_CHECK_FEE' => '0',
				'DISCOUNT_FEE' => '0',
				'RKSP_FEE' => '0',
				'HAWB' => '0',
				'HAWB_FEE' => '0',
				'HAWB_MAWB_FEE' => '0',
				'CSC_FEE' => $tpjkp2u,
				'ENVIROTAINER_ELEC_FEE' => '0',
				'ADDITIONAL_COSTS' => $tairport_surcharge,
				'NAWB_FEE' => '0',
				'BARCODE_FEE' => '0',
				'CARGO_DEVELOPMENT_FEE' => '0',
				'DUTIABLE_SHIPMENT_FEE' => '0',
				'FHL_FEE' => '0',
				'FWB_FEE' => '0',
				'CARGO_INSPECTION_REPORT_FEE' => '0',
				'MATERAI_FEE' => $tmaterai,
				'PPN_FEE' => $tppn
			);

			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 3,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $fields,
				CURLOPT_HTTPHEADER => array(
					'Cookie: dtCookie=CD78B9A24184B932B72CB79ED316B71D|X2RlZmF1bHR8MQ'
				),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			$response = json_decode($response, true);

			if(!$response){
				$status = 'no connection';
			}else{
				if($response['status'] == '200'){
					$status = 'berhasil';
				}elseif($response['status'] == '500'){
					$status = 'internal server eror';
				}else{
					$status = 'Error Unknown';
				}
			}
			$fields['PUSH_STATUS'] = $status;
			$fields['CREATE_BY'] = $_SESSION['name'];
			$fields['PAYMENT_ID'] = intval($data->last_id()->id) + $i + 1;
			$data_ap->insert_data($fields);
		}
		$values = substr($values,0,-1);
		$insert = $data->insert($values);

		?>
	</div>
	<script src="../assets/jquery/jquery-3.6.0.js"></script>
	<script src="../assets/printThis/printThis.js"></script>
	<script>
		$(document).ready(function(){
			
			$(".content").printThis({
				base:'true'
			});
			
			if(printThis){
				window.close();
			}
		})

	</script>
	<?php //header("location: ?"); ?>

	
</body>
</html>