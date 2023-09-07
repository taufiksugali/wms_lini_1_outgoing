<?php
session_start();
if ($_SESSION['print']=="off") {
	header("location: ../");
}
require_once('../config/config.php');
require_once('../models/database.php');
include('../models/m_print.php');

$connection = new Database($host, $user, $pass, $database);
$data = new Printdo($connection);
$d_print = $data->panggil($_GET['data']);
$print = $d_print->fetch_object();
$destination = $data->destination($print->flight_no)->fetch_object();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Print BTB</title>
	<link rel="stylesheet" href="../assets/css/print_do.css">
	<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
	<style>
		html,body{
			padding: 0;
			margin: 0;
		}
	</style>
	
</head>
<body id="do">
	<div class="judul">
		PT. POS LOGISTIK INDONESIA<br>
		TERMINAL KARGO BANDARA SOEKARNO HATTA <br>
		JAKARTA 19100 <br>
		TELP. 021-5500278 FAKS. 021-5500277
	</div>
	<div class="content text-center mt-2">
		BUKTI TIMBANG BARANG
		<div class = "field px-2 pt-2 text-start">
			
			<?php 
			if($print->smu_code == '' || $print->smu_code == '273' || $print->smu_code == '181'){
				$flight_number = explode("-", $print->flight_no);
				$curent_flight = 'PK-'.$flight_number[0];
			}else{
				$curent_flight = $print->flight_no;
			}
			?>
			<table>
				<tr>
					<td>DATE</td>
					<td>:</td>
					<td><?= date("d-m-Y H:i", strtotime($print->tanggal)) ?></td>
				</tr>
				<tr>
					<td>BTB NO</td>
					<td>:</td>
					<td><?= $print->no_do ?></td>
				</tr>
				<tr>
					<td>FLIGHT NO</td>
					<td>:</td>
					<td><?= $curent_flight ?></td>
				</tr>
				<tr>
					<td>DEST</td>
					<td>:</td>
					<td><?= $destination->destination ?></td>
				</tr>
				<tr>
					<td>AWB/SMU</td>
					<td>:</td>
					<td><?= $print->smu ?></td>
				</tr>
				<tr>
					<td>COMODITY</td>
					<td>:</td>
					<td><?= $print->comodity ?></td>
				</tr>
				<tr>
					<td>AGENT</td>
					<td>:</td>
					<td><?= $print->agent_name ?></td>
				</tr>
				<tr>
					<td>SHIPPER</td>
					<td>:</td>
					<td><?= $print->shipper_name ?></td>
				</tr>
				<tr>
					<td>NPWP</td>
					<td>:</td>
					<td></td>
				</tr>
				<tr>
					<td>PIC</td>
					<td>:</td>
					<td><?= $print->pic ?></td>
				</tr>
				<tr>
					<td>QTY</td>
					<td>:</td>
					<td><?= $print->quantity ?> koli</td>
				</tr>
				<tr>
					<td>WEIGHT</td>
					<td>:</td>
					<td><?= $print->weight ?> kg</td>
				</tr>
				<tr>
					<td>VOL</td>
					<td>:</td>
					<td><?= $print->volume ?> kg</td>
				</tr>
				<tr>
					<td>OFFICER</td>
					<td>:</td>
					<td><?= ucwords($print->proses_by) ?></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="text-center">
		================================
	</div>
	<?php 
	if(!isset($_GET['reprint'])){

	}else{ ?>

		<div class="mt-2">
			reprint_by: <?php echo $_SESSION['name']; ?>
		</div>
	<?php }
	?>
	<?php 
	if(!isset($_GET['revisi'])){

	}else{ ?>

		<div class="mt-2">
			revisi_by: <?php echo $print->last_editor; ?>
		</div>
	<?php }
	?>

	


	<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- jquery -->
	<script src="../assets/jquery/jquery-3.6.0.js"></script>
	
	<?php header("Refresh:0; url=../?page=btb"); ?>
	<script type="text/javascript">
		window.print();
	</script>

</body>
</html>	