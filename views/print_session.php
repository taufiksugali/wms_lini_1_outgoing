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
$d_print = $data->session($_GET['data']);


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Print DO</title>
	<link rel="stylesheet" href="../assets/css/print_session.css">
	<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
	<style>
		html,body{
			padding: 0;
			margin: 0;
		}
	</style>
	
</head>
<body id="do">
	<div class="row g-0 py-0 px-4 mb-2">
		<div class = "col-6">
			<div class="judul">
				<h3>Tonase session <?php echo $_GET['data']; ?></h3>
			</div>
		</div>
		<div class = "col-6">
			<div class = "row g-0 p-0 m-0">
				<div class="col-6">
					<label for="">Total smu : </label>
					<input type="text" id="smu" disabled> <br>
					<label for="">Total coly : </label>
					<input type="text" id="coly" disabled><br>
					<label for="">Total weight : </label>
					<input type="text" id="weight" disabled>
				</div>
				<div class="col-6">
					<label for="">Total volume : </label>
					<input type="text" id="volume" disabled><br>
					<label for="">Total revisi : </label>
					<input type="text" id="revisi" disabled><br>
					<label for="">Total cancel : </label>
					<input type="text" id="cancel" disabled>				
				</div>
			</div>
		</div>
	</div>
	
	<div class="d-flex justify-content-center px-4">
		<table>
			<thead>
				<tr>
					<th>No</th>
					<th>SMU</th>
					<th>BTB</th>
					<th>Comodity</th>
					<th>RA</th>
					<th>Agent</th>
					<th>PIC</th>
					<th>Quantity</th>
					<th>Weight</th>
					<th>Volume</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no=0;
				$coly=0;
				$weight=0;
				$volume=0;
				$revisi=0;
				$cancel=0;

				while ($print = $d_print->fetch_object()) :?>
					<?php 
					$no++;
					?>
					<tr>
						<td><?php echo $no; ?></td>
						<td><?php echo $print->smu; ?></td>
						<td><?php echo $print->no_do; ?></td>
						<td><?php echo $print->comodity; ?></td>
						<td><?php echo @$print->ra_name ; ?></td>
						<td><?php echo $print->agent_name; ?></td>
						<td><?php echo $print->pic; ?></td>
						<td><?php echo $print->quantity; ?></td>
						<td><?php echo $print->weight; ?></td>
						<td><?php echo $print->volume; ?></td>
						<td><?php echo date('d-m-y',strtotime($print->tanggal)); ?></td>
					</tr>
					<?php 
					$coly= $coly + $print->quantity;
					$weight= $weight + $print->weight;
					$volume= $volume + $print->volume;
					if ($print->status == 'revisi') {
						$revisi= $revisi + 1;
						// code...
					}
					if ($print->status == 'cancel') {
						$cancel= $cancel + 1;
						// code...
					}
					?>					
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
	

	


	<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- jquery -->
	<script src="../assets/jquery/jquery-3.6.0.js"></script>
	
	<?php //header("Refresh:0; url=../?page=btb"); ?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#smu').val("<?php echo $no.' smu'; ?>");
			$('#coly').val("<?php echo $coly.' coly'; ?>");
			$('#weight').val("<?php echo $weight.' kg'; ?>");
			$('#volume').val("<?php echo $volume.' kg'; ?>");
			$('#revisi').val("<?php echo $revisi.' smu'; ?>");
			$('#cancel').val("<?php echo $cancel.' smu'; ?>");
		})
		// window.print();
	</script>



</body>
</html>	