<?php
session_start();
require_once('../config/config.php');
require_once('../models/database.php');
include('../models/m_kasir.php');
$connection = new Database($host, $user, $pass, $database);
$data = new Kasir($connection);
$pricelist= $data->calprice()->fetch_object();
$admin = $pricelist->admin;
$sg = $pricelist->sg;
$kade = $pricelist->kade;
$pjkp2u = $pricelist->pjkp2u;
$materai = $pricelist->materai;
$airport_surcharge = $pricelist->airport_surcharge;

if(isset($_POST['reprint'])){
	$all = $data->joindata_print($_GET['data']);
	$result = $all->fetch_object();
	// var_dump($result); die();
}else{
	echo "<script>window.close()</script>";
}

if($result->weight >= 10 || $result->weight > $result->volume){
	$nett = $result->weight;
}elseif($result->weight <10 && $result->volume >= 10){
	$nett = $result->volume;
}elseif($result->weight <10 && $result->volume < 10){
	$nett = 10;
}

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
	<div class="contener ">
		<div class="content">
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
							: <?php echo $result->njg; ?><br>
							: <?php echo $result->agent_name; ?>/<?php echo $result->shipper_name; ?>/<?php echo $result->pic; ?><br>
							: <?php echo $result->smu; ?>/<?php echo $result->no_do; ?>
						</div>
					</div>
					<div class="kolom d-flex">
						<div class="info me-2">
							Tanggal BTB<br>
							Destination
						</div>
						<div >
							: <?php echo $result->tanggal; ?> <br>
							: <?php echo $result->tlc; ?>
						</div>
					</div>
				</div>
				<div class="d-flex justify-content-between px-4">
					<div>
						QTY: <?php echo $result->quantity; ?> koli
					</div>
					<div>
						Cargo Weight: <?php echo $result->weight; ?> kg
					</div>
					<div>
						Volume: <?php echo $result->volume; ?> kg
					</div>
					<div>
						Chargable Weight: <?php echo $nett; ?> kg
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
						: <?php echo $nett; ?> X 1 X <?php echo $sg; ?> <br>
						<br>
						: <?php echo $result->weight; ?> X 1 X <?php echo $pjkp2u; ?> <br>
						: <?php echo $result->weight; ?> X 1 X <?php echo $kade; ?> <br>
						: <?php echo $nett; ?> X 1 X <?php echo $airport_surcharge; ?> <br> 
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
						<?php echo number_format($result->sewa_gudang); ?> <br>
						<?php echo number_format($result->admin); ?> <br>
						<?php echo number_format($result->pjkp2u); ?> <br>
						<?php echo number_format($result->kade); ?> <br>
						<?php echo number_format($result->airport_tax); ?> <br>
						<?php echo number_format($result->ppn); ?> <br>
						<?php echo number_format($result->materai); ?> <br>
						<?php echo number_format($result->total); ?><br>
					</div>
				</div>
				<div class="terbilang">
					<?php 
					$terbilang = $data->terbilang($result->total);
					$penyebut = penyebut($terbilang);
					?>
					<i><?php echo ucwords($penyebut)." Rupiah"; ?></i>
				</div>
			</div>
			<div class="footer">
				<div class="d-flex justify-content-end">
					Cengkareng, <?php echo date("d-m-Y", strtotime($result->stimestamp)); ?>
				</div>
				<div class="d-flex justify-content-between">
					<div>
						Metode bayar: CASH
					</div>
					<div>
						Process by: <?php echo ucwords($result->proses_by); ?>
					</div>
				</div>
				<div class="d-flex justify-content-center">
					Reprint by: <?php echo ucwords($_SESSION['name']); ?> on <?php echo date("d-m-Y"); ?> 
				</div>				
			</div>
		</div>

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