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
$pricelist = $data->calprice()->fetch_object();
$admin = $pricelist->admin;
$sg = $pricelist->sg;
$kade = $pricelist->kade;
$pjkp2u = $pricelist->pjkp2u;
$materai = $pricelist->materai;
$airport_surcharge = $pricelist->airport_surcharge;
$pricelist_id = $pricelist->pricelist_id;
if (isset($_POST['print_invoice'])) {
	$npwp = $_POST['npwp'] == '' ? null : $_POST['npwp'];
	$agent = $_POST['d_agent'];
	$shipper = $_POST['d_shipper'];
	$d_smu = $_POST['d_smu'];
	// var_dump($d_smu); die();
	$d_smu = substr($d_smu, 0, -1);
	$smu = explode(",", $d_smu);
	$count = count($smu);
	$text = '';
	$s_count = 1;
	foreach ($smu as $key => $value) {
		if ($s_count == $count) {
			$text = $text . "'" . $value . "'";
		} else {
			$text = $text . "'" . $value . "',";
		}
		$s_count++;
	}
} else {
	echo "<script>window.close()</script>";
}

// die();
function penyebut($nilai)
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
		$values = "";
		$d_njg = $data->calnjg();
		$njg1 = $d_njg->fetch_object();
		$njg = $njg1->njg;
		$new_njg = $njg;
		for ($i = 0; $i < $count; $i++) {
			$new_njg = $new_njg + 1;
			$awb = $smu[$i];

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
			$ra_name = $result->ra_name;

			// if(!$pic){
			// 	echo "<script>window.close()</script>";
			// }



			// if($weight <= $volume && $volume <= 10 ){
			// 	$net=10;
			// }elseif($weight < $volume){
			// 	$net = $volume;
			// }else{$net = $weight;}

			if ($weight >= $volume && $weight > 10) {
				$net = $weight;
			} elseif ($volume > $weight && $volume > 10) {
				$net = $volume;
			} else {
				$net = 10;
			}



			// panggil data flight
			$p_flight = $data->callflight($flight);
			$f_result = $p_flight->fetch_object();

			$destination = $f_result->destination;
			$des_tlc = $f_result->tlc;
			$tlc = $f_result->tlc;

			if ($result->smu_code == '' || $result->smu_code == '273' || $result->smu_code == '181') {
				$send_flight = $f_result->flight_no;
			} else {
				$explodes = explode('-', $result->flight_no);
				$send_flight = $explodes[1];
			}

			// new data
			$tsg = $net * $sg;
			$tpjkp2u = $weight <= 10 ? 10 * $pjkp2u : $weight * $pjkp2u;
			$tkade = $net === 10 ? 10 * $kade : $weight * $kade;
			$tairport_surcharge = $net * $airport_surcharge;
			$tppn = round((($tsg + $tpjkp2u + $tkade + $admin + $tairport_surcharge) * 11) / 100);
			if (($tsg + $tpjkp2u + $tkade + $admin + $tairport_surcharge + $tppn) < 10000000) {
				$tmaterai = 0;
			} else {
				$tmaterai = $materai;
			}
			$total = round($tsg + $tpjkp2u + $tkade + $admin + $tairport_surcharge + $tppn + $tmaterai);
			$date = date("Y-m-d");
			$name = $_SESSION['name'];

			$values .= "('$new_njg','$btb','$awb','$date','$admin','$tsg','$tkade','$tpjkp2u','$tairport_surcharge','$tppn','$tmaterai','$total','$name','$s_kasir', '$pricelist_id', '$npwp'),"
		?>
			<div class="content">
				<div class="judul text-center position-relative pt-3">
					<h5>WH-Lini 1 - PT POS LOGISTIK INDONESIA</h5>
					<p>Terminal Kargo Bandara Soekarno-Hatta Jakarta 19100</p>
					<p>Telp. 021-5500278 Faks. 021-5500277</p>
					<div class="logo position-absolute start-0 top-0 pt-4">
						<img src="../assets/image/poslog.png" alt="poslog">
					</div>
				</div>
				<div class="data">
					<div class="d-flex justify-content-between">
						<div class="kolom d-flex">
							<div>
								<table class="table table-borderless p-0">
									<tr>
										<td class="p-0">
											<div class="d-flex justify-content-between"><span>No.NJG</span> <span class="ms-2">:</span></div>
										</td>
										<td class="pt-0 pe-0 pb-0"><?php echo $new_njg; ?></td>
									</tr>
									<tr>
										<td class="p-0">
											<div class="d-flex justify-content-between"><span>Agent/SHP/PIC</span> <span class="ms-2">:</span></div>
										</td>
										<td class="pt-0 pe-0 pb-0"><?php echo $agent; ?>/<?php echo $shipper; ?>/<?php echo $pic; ?></td>
									</tr>
									<tr>
										<td class="p-0">
											<div class="d-flex justify-content-between"><span>AWB/BTB</span> <span class="ms-2">:</span></div>
										</td>
										<td class="pt-0 pe-0 pb-0"><?php echo $awb; ?>/<?php echo $btb; ?></td>
									</tr>
									<tr>
										<td class="p-0">
											<div class="d-flex justify-content-between"><span class="text-nowrap">Nama Pembeli/Agent</span> <span class="ms-2">:</span></div>
										</td>
										<td class="pt-0 pe-0 pb-0"><?php echo $agent; ?></td>
									</tr>
									<tr>
										<td class="p-0">
											<div class="d-flex justify-content-between"><span class="text-nowrap">NPWP Pembeli/Agent</span> <span class="ms-2">:</span></div>
										</td>
										<td class="pt-0 pe-0 pb-0"><?php echo @$npwp; ?></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="kolom d-flex">
							<div>
								<table class="table table-borderless p-0">
									<tr>
										<td class="p-0">
											<div class="d-flex justify-content-between"><span class="text-nowrap">Tanggal BTB</span> <span class="ms-2">:</span></div>
										</td>
										<td class="pt-0 pe-0 pb-0"><?php echo $date_btb; ?></td>
									</tr>
									<tr>
										<td class="p-0">
											<div class="d-flex justify-content-between"><span>Destination</span> <span class="ms-2">:</span></div>
										</td>
										<td class="pt-0 pe-0 pb-0"><?php echo $tlc; ?></td>
									</tr>
									<tr>
										<td class="p-0">
											<div class="d-flex justify-content-between"><span>RA</span> <span class="ms-2">:</span></div>
										</td>
										<td class="pt-0 pe-0 pb-0"><?php echo @$ra_name; ?></td>
									</tr>
								</table>
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
					<div>
						<table class="table table-sm table-borderless p-0 table-td-valign-middle">
							<tr>
								<td class="p-0 ps-4" width="40%">
									Jasa Gudang
								</td>
								<td class="p-0" width="30%">
									<?php echo $net; ?> X 1 X <?php echo $sg; ?>
								</td>
								<td class="p-0 pe-4" width="30%">
									<div class="d-flex justify-content-between">
										<span class="ps-4">=Rp.</span>
										<span><?php echo number_format($tsg); ?></span>
									</div>
								</td>
							</tr>
							<tr>
								<td class="p-0 ps-4" width="40%">
									Admin
								</td>
								<td class="p-0" width="30%">

								</td>
								<td class="p-0 pe-4" width="30%">
									<div class="d-flex justify-content-between">
										<span class="ps-4">=Rp.</span>
										<span><?php echo number_format($admin); ?></span>
									</div>
								</td>
							</tr>
							<tr>
								<td class="p-0 ps-4" width="40%">
									JKP2U
								</td>
								<td class="p-0" width="30%">
									<?php echo $weight; ?> X 1 X <?php echo $pjkp2u; ?>
								</td>
								<td class="p-0 pe-4" width="30%">
									<div class="d-flex justify-content-between">
										<span class="ps-4">=Rp.</span>
										<span><?php echo number_format($tpjkp2u); ?></span>
									</div>
								</td>
							</tr>
							<tr>
								<td class="p-0 ps-4" width="40%">
									Kade
								</td>
								<td class="p-0" width="30%">
									<?php echo $weight; ?> X 1 X <?php echo $kade; ?>
								</td>
								<td class="p-0 pe-4" width="30%">
									<div class="d-flex justify-content-between">
										<span class="ps-4">=Rp.</span>
										<span><?php echo number_format($tkade); ?></span>
									</div>
								</td>
							</tr>
							<tr>
								<td class="p-0 ps-4" width="40%">
									Airport Surcharge
								</td>
								<td class="p-0" width="30%">
									<?php echo $net; ?> X 1 X <?php echo $airport_surcharge; ?>
								</td>
								<td class="p-0 pe-4" width="30%">
									<div class="d-flex justify-content-between">
										<span class="ps-4">=Rp.</span>
										<span><?php echo number_format($tairport_surcharge); ?></span>
									</div>
								</td>
							</tr>
							<tr class="border-top border-dark ">
								<td class="p-0 ps-4" width="40%">
									Total Tagihan
								</td>
								<td class="p-0" width="30%">
								</td>
								<td class="p-0 pe-4" width="30%">
									<div class="d-flex justify-content-between">
										<span class="ps-4">=Rp.</span>
										<span><?php echo number_format($tsg + $admin + $tpjkp2u + $tkade + $tairport_surcharge); ?></span>
									</div>
								</td>
							</tr>
							<tr>
								<td class="p-0 ps-4" width="40%">
									PPN (11%)
								</td>
								<td class="p-0" width="30%">
								</td>
								<td class="p-0 pe-4" width="30%">
									<div class="d-flex justify-content-between">
										<span class="ps-4">=Rp.</span>
										<span><?php echo number_format($tppn); ?></span>
									</div>
								</td>
							</tr>
							<tr>
								<td class="p-0 ps-4" width="40%">
									Materai
								</td>
								<td class="p-0" width="30%">
								</td>
								<td class="p-0 pe-4" width="30%">
									<div class="d-flex justify-content-between">
										<span class="ps-4">=Rp.</span>
										<span><?php echo number_format($tmaterai); ?></span>
									</div>
								</td>
							</tr>
							<tr>
								<td class="p-0 ps-4" width="40%">
									Total Bayar
								</td>
								<td class="p-0" width="30%">
								</td>
								<td class="p-0 pe-4" width="30%">
									<div class="d-flex justify-content-between">
										<span class="ps-4">=Rp.</span>
										<span><strong><?php echo number_format($total); ?></strong></span>
									</div>
								</td>
							</tr>
						</table>
					</div>
					<div class="terbilang">
						<?php
						$terbilang = $data->terbilang($total);
						$penyebut = penyebut($terbilang);
						?>
						<i><strong>Terbilang : </strong><?php echo ucwords($penyebut) . " Rupiah"; ?></i>
					</div>
				</div>
				<div class="footer">
					<div class="d-flex justify-content-end">
						Cengkareng, <?php echo date("d-m-Y", strtotime($date)); ?>
					</div>
					<div class="d-flex justify-content-between mb-3">
						<div>
							Metode bayar: CASH
						</div>
						<div>
							Process by: <?php echo $name; ?>
						</div>
					</div>
					<div class="d-flex justify-content-between">
						<div class="ms-4">
							PT Pos Logistik Indonesia <br>
							Jl. Lapangan Banteng Utara No. 1 , Pasar Baru, Sawah Besar, Jakarta Pusat 10710 <br>
							NPWP: <strong>0314651654075000</strong> <br>
							Dokumen ini merupakan dokumen dipersamakan dengan Faktur Pajak<br>
							sesuai dengan Peraturan Direktur Jendral Pajak Nomor 16/PJ/2021
						</div>
						<div class="me-4">
							<img src="../assets/phpqrcode-master/qrgenerator.php?data=<?= $new_njg . '|' . $_SESSION['id'] . '|' . $_SESSION['name'] ?>" alt="QR Code 1" width="120px">
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

			if (!$response) {
				$status = 'no connection';
			} else {
				if ($response['status'] == '200') {
					$status = 'berhasil';
				} elseif ($response['status'] == '500') {
					$status = 'internal server eror';
				} else {
					$status = 'Error Unknown';
				}
			}
			$fields['PUSH_STATUS'] = $status;
			$fields['CREATE_BY'] = $_SESSION['name'];
			$fields['PAYMENT_ID'] = intval($data->last_id()->id) + $i + 1;
			$data_ap->insert_data($fields);
		}
		$values = substr($values, 0, -1);
		$insert = $data->insert($values);

		?>
	</div>
	<script src="../assets/jquery/jquery-3.6.0.js"></script>
	<script src="../assets/printThis/printThis.js"></script>
	<script>
		$(document).ready(function() {

			$(".content").printThis({
				base: 'true',
				afterPrint: function() {
					window.close();
				}
			});

			window.onafterprint = function() {
				window.close();
			};

		})
	</script>
	<?php //header("location: ?"); 
	?>


</body>

</html>