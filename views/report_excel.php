<?php
require_once('../config/config.php');
require_once('../models/database.php');
include('../models/m_report.php');
$connection = new Database($host, $user, $pass, $database);
$data = new Report($connection);
if (isset($_POST['unduh'])) {
	$date1 = $_POST['tanggal_awal'];
	$date2 = $_POST['tanggal_akhir'];
	$airline = $_POST['airline'];
	$filename = "data " . date("Y-m-d") . ".xls";
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename= $filename");
?>


	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title></title>
		<style type="text/css">
			td {
				border: solid 1px black;
			}
		</style>
	</head>

	<body>
		Data tanggal <?php echo $date1 ?> s/d <?php echo $date2 ?>
		<table class="mt-5" id="allReportOutgoing" style="border: solid 1px black">
			<thead>
				<tr>
					<th class="text-center px-1">No</th>
					<th class="text-center">SMU</th>
					<th class="text-center">BTB</th>
					<th class="text-center">Date_of_BTB</th>
					<th class="text-center">NJG</th>
					<th class="text-center">Date_of_NJG</th>
					<th class="text-center px-3">Agent_name</th>
					<th class="text-center px-3">Shipper_name</th>
					<th class="text-center px-3">PIC</th>
					<th class="text-center">Flight</th>
					<th class="text-center">Destination</th>
					<th class="text-center">Comodity</th>
					<th class="text-center">Quantity</th>
					<th class="text-center">Gross Weight</th>
					<th class="text-center">Weight</th>
					<th class="text-center">Volume</th>
					<th class="text-center">Nett</th>
					<th class="text-center">Admin</th>
					<th class="text-center">Sg</th>
					<th class="text-center">Kade</th>
					<th class="text-center">Ap2</th>
					<th class="text-center">A_Surcharge</th>
					<th class="text-center">Ppn</th>
					<th class="text-center">Materai</th>
					<th class="text-center">Total</th>
					<th class="text-center">Session_Of_BTB</th>
					<th class="text-center">BTB_issued_by</th>
					<th class="text-center">Session_Of_NJG</th>
					<th class="text-center">NJG_issued_by</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$angka = 0;
				if ($airline == 'all') {
					$takedata = $data->allbydate($date1, $date2, "complete");
				} else {
					$takedata = $data->allbydate_airline($date1, $date2, "complete", $airline);
				}

				$quantity = 0;
				$weight = 0;
				$nweight = 0;
				$volume = 0;
				$net = 0;
				$admin = 0;
				$sg = 0;
				$kade = 0;
				$ap2 = 0;
				$airtax = 0;
				$ppn = 0;
				$materai = 0;
				$g_total = 0;
				while ($result = $takedata->fetch_object()): ?>
					<?php
					$angka++;
					?>
					<tr>
						<td class="text-center px-1"><?php echo $angka; ?></td>
						<td class="text-center px-3"><?php echo $result->smu ?></td>
						<td class="text-start px-3"><?php echo $result->no_do ?></td>
						<td class="text-start px-3"><?php echo $result->tanggalbtb ?></td>
						<td class="text-start px-3"><?php echo $result->njg ?></td>
						<td class="text-start px-3"><?php echo $result->tanggalnjg ?></td>
						<td class="text-center px-3"><?php echo $result->agent_name ?></td>
						<td class="text-center px-3"><?php echo $result->shipper_name ?></td>
						<td class="text-center px-3"><?php echo $result->pic ?></td>
						<td class="text-center px-3"><?php echo $result->flight_no ?></td>
						<td class="text-center px-3"><?php echo $result->tlc ?></td>
						<td class="text-center px-3"><?php echo $result->comodity ?></td>
						<td class="text-center px-3"><?php echo $result->quantity ?></td>
						<td class="text-center px-3"><?php echo $result->weight ?></td>
						<td class="text-center px-3"><?php echo $n_weight = $result->weight < 10 ? 10 : $result->weight; ?></td>
						<td class="text-center px-3"><?php echo $result->volume ?></td>
						<td class="text-center px-3">
							<?php
							$a = $result->weight;
							$b = $result->volume;
							if ($a <= 10 && $b <= 10) {
								$nett = 10;
							} elseif ($a > 10 && $a >= $b) {
								$nett = $a;
							} elseif ($b > 10 && $b > $a) {
								$nett = $b;
							}
							echo $nett;
							?>
						</td>
						<td class="text-center px-3"><?php echo $result->admin ?></td>
						<td class="text-center px-3"><?php echo $result->sewa_gudang ?></td>
						<td class="text-center px-3"><?php echo $result->kade ?></td>
						<td class="text-center px-3"><?php echo $result->pjkp2u ?></td>
						<td class="text-center px-3"><?php echo $result->airport_tax ?></td>
						<td class="text-center px-3"><?php echo $result->ppn ?></td>
						<td class="text-center px-3"><?php echo $result->materai ?></td>
						<td class="text-center px-3"><?php echo $result->total ?></td>
						<td class="text-center px-3"><?php echo $result->session ?></td>
						<td class="text-center px-3"><?php echo $result->proses_btb ?></td>
						<td class="text-center px-3"><?php echo $result->session_kasir ?></td>
						<td class="text-center px-3"><?php echo $result->proses_njg ?></td>
					</tr>
					<?php
					$quantity = $quantity + $result->quantity;
					$weight = $weight + $result->weight;
					$nweight = $nweight + $n_weight;
					$volume = $volume + $result->volume;
					$net = $net + $nett;
					$admin = $admin + $result->admin;
					$sg = $sg + $result->sewa_gudang;
					$kade = $kade + $result->kade;
					$ap2 = $ap2 + $result->pjkp2u;
					$airtax = $airtax + $result->airport_tax;
					$ppn = $ppn + $result->ppn;
					$materai = $materai + $result->materai;
					$g_total = $g_total + $result->total;
					?>
				<?php endwhile;
				?>
				<tr>
					<td></td>
					<td>Total</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php echo $quantity ?></td>
					<td><?php echo $weight ?></td>
					<td><?php echo $nweight ?></td>
					<td><?php echo $volume ?></td>
					<td><?php echo $net ?></td>
					<td><?php echo $admin ?></td>
					<td><?php echo $sg ?></td>
					<td><?php echo $kade ?></td>
					<td><?php echo $ap2 ?></td>
					<td><?php echo $airtax ?></td>
					<td><?php echo $ppn ?></td>
					<td><?php echo $materai ?></td>
					<td><?php echo $g_total ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>

			</tbody>
		</table>

		<br><br>Table void
		<table class="" id="allReportOutgoingVoid" style="border: solid 1px black">
			<thead>
				<tr>
					<th class="text-center" style="width: fit-content;">No</th>
					<th class="text-center">SMU</th>
					<th class="text-center">BTB</th>
					<th class="text-center">Date_of_BTB</th>
					<th class="text-center">NJG</th>
					<th class="text-center">Date_of_NJG</th>
					<th class="text-center px-3">Agent_name</th>
					<th class="text-center px-3">Shipper_name</th>
					<th class="text-center px-3">PIC</th>
					<th class="text-center">Flight</th>
					<th class="text-center">Destination</th>
					<th class="text-center">Comodity</th>
					<th class="text-center">Quantity</th>
					<th class="text-center">Gross Weight</th>
					<th class="text-center">Weight</th>
					<th class="text-center">Volume</th>
					<th class="text-center">Nett</th>
					<th class="text-center">Admin</th>
					<th class="text-center">Sg</th>
					<th class="text-center">Kade</th>
					<th class="text-center">Ap2</th>
					<th class="text-center">A_Surcharge</th>
					<th class="text-center">Ppn</th>
					<th class="text-center">Materai</th>
					<th class="text-center">Total</th>
					<th class="text-center">Session_Of_BTB</th>
					<th class="text-center">BTB_issued_by</th>
					<th class="text-center">Session_Of_NJG</th>
					<th class="text-center">NJG_issued_by</th>
					<th class="text-center">keterangan</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ($airline == 'all') {
					$takedatavoid = $data->allbydate($date1, $date2, "void");
				} else {
					$takedatavoid = $data->allbydate_airline($date1, $date2, "void", $airline);
				}
				$nomor = 0;

				$quantityvoid = 0;
				$weightvoid = 0;
				$vnweight = 0;
				$volumevoid = 0;
				$netvoid = 0;
				$adminvoid = 0;
				$sgvoid = 0;
				$kadevoid = 0;
				$ap2void = 0;
				$airtaxvoid = 0;
				$ppnvoid = 0;
				$materaivoid = 0;
				$g_totalvoid = 0;
				while ($resultvoid = $takedatavoid->fetch_object()): ?>
					<?php
					$nomor++
					?>
					<tr>
						<td class="text-center px-3" style="width: fit-content;"><?php echo $nomor ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->smu ?></td>
						<td class="text-start px-3"><?php echo $resultvoid->no_do ?></td>
						<td class="text-start px-3"><?php echo $resultvoid->tanggalbtb ?></td>
						<td class="text-start px-3"><?php echo $resultvoid->njg ?></td>
						<td class="text-start px-3"><?php echo $resultvoid->tanggalnjg ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->agent_name ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->shipper_name ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->pic ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->flight_no ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->tlc ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->comodity ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->quantity ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->weight ?></td>
						<td class="text-center px-3"><?php echo $vn_weight = $resultvoid->weight < 10 ? 10 : $resultvoid->weight; ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->volume ?></td>
						<td class="text-center px-3">
							<?php
							$c = $resultvoid->weight;
							$d = $resultvoid->volume;
							if ($c <= 10 && $d <= 10) {
								$nettvoid = 10;
							} elseif ($c > 10 && $c >= $d) {
								$nettvoid = $a;
							} elseif ($d > 10 && $d > $c) {
								$nettvoid = $b;
							}
							echo $nettvoid;
							?>
						</td>
						<td class="text-center px-3"><?php echo $resultvoid->admin ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->sewa_gudang ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->kade ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->pjkp2u ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->airport_tax ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->ppn ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->materai ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->total ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->session ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->proses_btb ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->session_kasir ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->proses_njg ?></td>
						<td class="text-center px-3"><?php echo $resultvoid->keterangan ?></td>
					</tr>
					<?php

					$quantityvoid = $quantityvoid + $resultvoid->quantity;
					$weightvoid = $weightvoid + $resultvoid->weight;
					$vnweight = $vnweight + +$vn_weight;
					$volumevoid = $volumevoid + $resultvoid->volume;
					$netvoid = $netvoid + $nettvoid;
					$adminvoid = $adminvoid + $resultvoid->admin;
					$sgvoid = $sgvoid + $resultvoid->sewa_gudang;
					$kadevoid = $kadevoid + $resultvoid->kade;
					$ap2void = $ap2void + $resultvoid->pjkp2u;
					$airtaxvoid = $airtaxvoid + $resultvoid->airport_tax;
					$ppnvoid = $ppnvoid + $resultvoid->ppn;
					$materaivoid = $materaivoid + $resultvoid->materai;
					$g_totalvoid = $g_totalvoid + $resultvoid->total;

					?>
				<?php endwhile;
				?>
				<tr>
					<td></td>
					<td>Total</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php echo $quantityvoid ?></td>
					<td><?php echo $weightvoid ?></td>
					<td><?php echo $vnweight ?></td>
					<td><?php echo $volumevoid ?></td>
					<td><?php echo $netvoid ?></td>
					<td><?php echo $adminvoid ?></td>
					<td><?php echo $sgvoid ?></td>
					<td><?php echo $kadevoid ?></td>
					<td><?php echo $ap2void ?></td>
					<td><?php echo $airtaxvoid ?></td>
					<td><?php echo $ppnvoid ?></td>
					<td><?php echo $materaivoid ?></td>
					<td><?php echo $g_totalvoid ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>

			</tbody>
		</table>



	</body>

	</html>
<?php }; ?>