<?php 
session_start();
require('../config/config.php');
require('../models/database.php');
require('../models/m_manifest.php');
$connection = new Database($host, $user, $pass, $database);
$data = new Manifest($connection);
$code_manifest = $_POST['codemanifest'];
$distinctdata= $data->getdistinct($code_manifest);
$first_table = $data->dlsRegular($code_manifest);
$second_table = $data->dlsBulk($code_manifest);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0" > -->
	<title>Print DLS</title>
	<link rel="stylesheet" href="../assets/css/print_dls.css" />
	<link href="../assets/fontawesome/css/all.css" rel="stylesheet"	crossorigin="anonymous"	/>
	<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" />
	
</head>
<body>
	<!-- As a heading -->
	<nav class="navbar navbar-dark bg-success bg-gradient position-fixed">
		<div class="container-fluid">
			<span class="navbar-brand mb-0 h1">Cetak DLS</span>
			<div>
				<button type="button" id="print" class="ms-5 me-3 btn btn-dark">
					print
				</button>
			</div>
		</div>
	</nav>
</div>
<div class="position-relative bg-dark bg-gradient" style="height: 5rem;width: 100%;">
</div>
<!-- print target -->
<div id="target-print" class="target">
	<div id="pembungkus">
		<div class="text-center pt-3" id="judul">
			<h2>RIMBUN AIR</h2>
			<p>DEAD LOAD STATEMENT<br>ALL WEIGHT IN KILOGRAM</p>
		</div>

		<table id="firstTable">
			<thead>
				<tr>
					<th rowspan="4">No</th>
					<th>Flight No</th>
					<th colspan="3">Station of Loading</th>
					<th>Date</th>
					<th>Aircraft Reg</th>
					<th rowspan="4">Loading Position</th>
				</tr>
				<tr>
					<th>PK-<?php $pk = explode('-', $distinctdata->flight_no); echo $pk[0] ?></th>
					<th colspan="3">CGK</th>
					<th><?= date('d/m/Y', strtotime($distinctdata->tanggal)) ?></th>
					<th></th>
				</tr>
				<tr>
					<th rowspan="2">ULD</th>
					<th colspan="3">Cargo Weight</th>
					<th>Station Of</th>
					<th rowspan="2">Remarks</th>
				</tr>
				<tr>
					<th>Gross</th>
					<th>Tare</th>
					<th>NETT</th>
					<th>Unloading</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$first_no = 0;
				$total_weight = 0;
				$total_tare = 0;
				$total_gross = 0;
				while($result1 = $first_table->fetch_object()){
					$first_no ++;

					// $current_tare = ($result1->type === 'PAG')? 100 : ($result1->type == 'PKC') ? 45 : 0;
					if($result1->type === 'PAG'){
						$current_tare = 100;
					}elseif($result1->type === 'PKC'){
						$current_tare = 45;
					}else{
						$current_tare = 0;
					}
					?>
					<tr>
						<td class="text-nowrap"><?= $first_no ?>.</td>
						<td><?= str_replace(' FREELOAD', '', $result1->dls)  ?></td>
						<td><?= $result1->total_berat+$current_tare ?></td>
						<td><?= $current_tare ?></td>
						<td><?= $result1->total_berat ?></td>
						<td><?= strtoupper($result1->registration) ?></td>
						<td class="text-nowrap"><?= strtoupper($result1->comodity) ?></td>
						<td><?= ($result1->type == 'PAG FREELOAD' || $result1->type == 'PKC FREELOAD')? 'FREELOAD':'' ?></td>
					</tr>
					<?php
					$total_weight = $total_weight + $result1->total_berat;
					$total_tare = $total_tare+$current_tare;
					$total_gross = $total_gross + $result1->total_berat+$current_tare;
				}

				for ($n=$first_no+1; $n <=9 ; $n++) : ?>
					<tr>
						<td><?= $n ?>.</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				<?php 	
				endfor;
				?>
				
				<tr>
					<td></td>
					<td>Total</td>
					<td><?= $total_gross ?></td>
					<td><?= $total_tare ?></td>
					<td><?= $total_weight ?></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>

		<table class="mt-5" id="secondTable">
			<thead>
				<tr>
					<th rowspan="2">No</th>
					<th>BULK</th>
					<th rowspan="2">Pieces</th>
					<th rowspan="2">Weight</th>
					<th rowspan="2">Dest</th>
					<th rowspan="2">Remarks</th>
					<th rowspan="2"></th>
					<th rowspan="2"></th>
				</tr>
				<tr>
					<th>Number Of</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$nomor = 0;
				$bulk_total_koli = 0;
				$bulk_total_berat = 0;
				while ($result2 = $second_table->fetch_object()) {
					$nomor++;
					?>
					<tr>
						<td><?= $nomor ?>.</td>
						<td><?= $result2->dls ?></td>
						<td><?= $result2->total_koli ?></td>
						<td><?= $result2->total_berat ?></td>
						<td><?= $result2->registration ?></td>
						<td><?= $result2->remarks ?></td>
						<td></td>
						<td></td>
					</tr>
					<?php
					$bulk_total_koli = $bulk_total_koli + $result2->total_koli;
					$bulk_total_berat = $bulk_total_berat + $result2->total_berat;
				}
				for ($i=$nomor+1; $i <=8 ; $i++) : ?>
					<tr>
						<td><?= $i ?>.</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				<?php
				endfor;
				?>
				
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td rowspan="2" style="width: 5rem"><?= $bulk_total_koli ?></td>
					<td rowspan="2" style="width: 5rem"><?= $bulk_total_berat ?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>

		<div class="py-2 text-center">
			<p>OTHER INFORMATION</p>
		</div>
		<table id="thirdTable">
			<tbody>
				<tr>
					<td>1</td>
					<td class="text-start fw-bold fst-italic">TOTAL GROSS WEIGT</td>
					<td><?= $total_gross + $bulk_total_berat ?></td>
					<td>Kgs</td>
				</tr>
				<tr>
					<td>2</td>
					<td class="text-start fw-bold fst-italic">ULD TARE TOTAL</td>
					<td><?= $total_tare ?></td>
					<td>Kgs</td>
				</tr>
				<tr>
					<td>3</td>
					<td class="text-start fw-bold fst-italic">TOTAL NETT WEIGHT</td>
					<td><?= $total_weight + $bulk_total_berat ?></td>
					<td>Kgs</td>
				</tr>
			</tbody>
		</table>
		<div class=" d-flex justify-content-between mt-4 mb-3" id="ttd">
			<div class="ttd ms-2">
				<div class="mb-4">Prerpared By</div>
				<div class="d-flex justify-content-between">
					<div>(</div>
					<div class="mx-1"><?= ucfirst($distinctdata->creator) ?></div>
					<div>)</div>
				</div>
			</div>
			<div class="ttd">
				<div class="mb-4">Acknowledge By</div>
				<div class="d-flex justify-content-between">
					<div>(</div>
					<div></div>
					<div>)</div>
				</div>
			</div>
			<div class="ttd me-2">
				<div class="mb-4">Approved By Airline</div>
				<div class="d-flex justify-content-between">
					<div>(</div>
					<div></div>
					<div>)</div>
				</div>
			</div>
		</div>

	</div>  
</div>
<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jquery -->
<script src="../assets/jquery/jquery-3.6.0.js"></script>
<!-- print this -->
<script src="../assets/printThis/printThis.js"></script>
<script src="../assets/PrintArea-master/js/jquery.printarea.js"></script>
<script>
	$(document).ready(function () {
		$("#print").click(function () {
			$("#target-print").printArea();
		});		
	});
</script>
</body>
</html>
