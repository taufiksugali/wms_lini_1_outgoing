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

$invoiceIds = $_POST['invoice_ids'];
$invoiceIds = json_decode($invoiceIds);
$string = '(';
$keyNumb = 0;
foreach ($invoiceIds as $key => $value) {
	if ($keyNumb == 0) {
		$string .= "'" . $value . "'";
	} else {
		$string .= ", '" . $value . "'";
	}
	$keyNumb++;
}
$string .= ')';
$invoice = $data->getInvoiceByIds($string);
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
		while ($result = $invoice->fetch_object()) {
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
										<td class="pt-0 pe-0 pb-0"><?php echo $result->njg; ?></td>
									</tr>
									<tr>
										<td class="p-0">
											<div class="d-flex justify-content-between"><span>Agent/SHP/PIC</span> <span class="ms-2">:</span></div>
										</td>
										<td class="pt-0 pe-0 pb-0"><?php echo $result->agent_name; ?>/<?php echo $result->shipper_name; ?>/<?php echo $result->pic; ?></td>
									</tr>
									<tr>
										<td class="p-0">
											<div class="d-flex justify-content-between"><span>AWB/BTB</span> <span class="ms-2">:</span></div>
										</td>
										<td class="pt-0 pe-0 pb-0"><?php echo $result->smu; ?>/<?php echo $result->no_do; ?></td>
									</tr>
									<tr>
										<td class="p-0">
											<div class="d-flex justify-content-between"><span class="text-nowrap">Nama Pembeli/Agent</span> <span class="ms-2">:</span></div>
										</td>
										<td class="pt-0 pe-0 pb-0"><?php echo $result->agent_name; ?></td>
									</tr>
									<tr>
										<td class="p-0">
											<div class="d-flex justify-content-between"><span class="text-nowrap">NPWP Pembeli/Agent</span> <span class="ms-2">:</span></div>
										</td>
										<td class="pt-0 pe-0 pb-0"><?php echo @$result->npwp; ?></td>
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
										<td class="pt-0 pe-0 pb-0"><?php echo $result->btb_date; ?></td>
									</tr>
									<tr>
										<td class="p-0">
											<div class="d-flex justify-content-between"><span>Destination</span> <span class="ms-2">:</span></div>
										</td>
										<td class="pt-0 pe-0 pb-0"><?php echo $result->tlc; ?></td>
									</tr>
									<tr>
										<td class="p-0">
											<div class="d-flex justify-content-between"><span>RA</span> <span class="ms-2">:</span></div>
										</td>
										<td class="pt-0 pe-0 pb-0"><?php echo @$result->ra_name; ?></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div class="d-flex justify-content-between px-4">
						<div>
							QTY: <?= $result->quantity; ?>
						</div>
						<div>
							Actual Weight: <?= $result->weight; ?>
						</div>
						<div>
							Volumetric Weight: <?= $result->volume; ?>
						</div>
						<div>
							<?php
							if ($result->weight > $result->volume) {
								if ($result->weight > 10) {
									$net = $result->weight;
								} else {
									$net = 10;
								}
							} else {
								if ($result->volume > 10) {
									$net = $result->volume;
								} else {
									$net = 10;
								}
							}
							?>
							Chargable Weight: <?= $net; ?>
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
									<?php echo $net; ?> X 1 X <?php echo $result->pl_sg; ?>
								</td>
								<td class="p-0 pe-4" width="30%">
									<div class="d-flex justify-content-between">
										<span class="ps-4">=Rp.</span>
										<span><?php echo number_format($result->payment_sg); ?></span>
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
										<span><?php echo number_format($result->payment_admin); ?></span>
									</div>
								</td>
							</tr>
							<tr>
								<td class="p-0 ps-4" width="40%">
									JKP2U
								</td>
								<td class="p-0" width="30%">
									<?php echo $result->weight; ?> X 1 X <?php echo $result->pl_pjkp2u; ?>
								</td>
								<td class="p-0 pe-4" width="30%">
									<div class="d-flex justify-content-between">
										<span class="ps-4">=Rp.</span>
										<span><?php echo number_format($result->payment_pjkp2u); ?></span>
									</div>
								</td>
							</tr>
							<tr>
								<td class="p-0 ps-4" width="40%">
									Kade
								</td>
								<td class="p-0" width="30%">
									<?php echo $result->weight; ?> X 1 X <?php echo $result->pl_kade; ?>
								</td>
								<td class="p-0 pe-4" width="30%">
									<div class="d-flex justify-content-between">
										<span class="ps-4">=Rp.</span>
										<span><?php echo number_format($result->payment_kade); ?></span>
									</div>
								</td>
							</tr>
							<tr>
								<td class="p-0 ps-4" width="40%">
									Airport Surcharge
								</td>
								<td class="p-0" width="30%">
									<?php echo $net; ?> X 1 X <?php echo $result->pl_airtax; ?>
								</td>
								<td class="p-0 pe-4" width="30%">
									<div class="d-flex justify-content-between">
										<span class="ps-4">=Rp.</span>
										<span><?php echo number_format($result->payment_airtax); ?></span>
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
										<span><?php echo number_format($result->payment_admin + $result->payment_sg + $result->payment_pjkp2u + $result->payment_kade + $result->payment_airtax); ?></span>
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
										<span><?php echo number_format($result->payment_ppn); ?></span>
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
										<span><?php echo number_format($result->payment_materai); ?></span>
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
										<span><strong><?php echo number_format($result->payment_total); ?></strong></span>
									</div>
								</td>
							</tr>
						</table>
					</div>
					<div class="terbilang">
						<?php
						$terbilang = $data->terbilang($result->payment_total);
						$penyebut = penyebut($terbilang);
						?>
						<i><strong>Terbilang : </strong><?php echo ucwords($penyebut) . " Rupiah"; ?></i>
					</div>
				</div>
				<div class="footer">
					<div class="d-flex justify-content-end">
						Cengkareng, <?php echo date("d-m-Y", strtotime($result->payment_date)); ?>
					</div>
					<div class="d-flex justify-content-between mb-3">
						<div>
							Metode bayar: CASH
						</div>
						<div>
							Process by: <?php echo $result->payment_cashier; ?>
						</div>
					</div>
					<div class="d-flex justify-content-between">
						<div class="ms-4">
							PT Pos Logistik Indonesia <br>
							Jl. Lapangan Banteng Utara No. 1 , Pasar Baru, Sawah Besar, Jakarta Pusat 10710 <br>
							NPWP: <strong>0314651654075000</strong> <br>
							<div class="border border-dark ps-2">
								Dokumen ini merupakan dokumen yang dipersamakan dengan Faktur Pajak<br>
								sesuai dengan Peraturan Direktur Jenderal Pajak Nomor 16/PJ/2021
							</div>
						</div>
						<div class="me-4">
							<img src="../assets/phpqrcode-master/qrgenerator.php?data=<?= $result->njg . '|' . $_SESSION['id'] . '|' . $result->payment_cashier ?>" alt="QR Code 1" width="120px">
						</div>
					</div>
				</div>
			</div>
		<?php
		}
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


</body>

</html>