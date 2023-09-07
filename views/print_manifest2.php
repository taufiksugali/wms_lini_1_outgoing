<?php 
error_reporting(0);
session_start();
require('../config/config.php');
require('../models/database.php');
require('../models/m_manifest.php');
$connection = new Database($host, $user, $pass, $database);
$data = new Manifest($connection);



// create-manifest
if(isset($_POST['create'])){
	$variable = $_POST['textArea'];
	$variable = substr($variable,0,-1);
	$var = explode(",",$variable);
	$val = "";
	for($i=0; $i < count($var); $i = $i + 10){
		$h = $i+10;
		$ks = "'";
		for ($g=$i; $g <$h ; $g++) { 
			$ks = $ks.$var[$g]."','";
		}
		$ks = substr($ks,0,-2);
		$newks = "(".$ks.")";
		$val = $val.$newks.",";
	}
	$val = substr($val,0,-1);

	for ($d=0; $d < count($var) ; $d = $d + 12) {
		$f =$d+12;
		$smu = $d+1;
		$qty = $d+2;
		$weight = $d+3;
		for ($m=$d; $m < $f ; $m++) { 
			$info = "('".$var[$d]."',"."'".$var[$smu]."',"."'".$var[$qty]."',"."'".$var[$weight]."',"."'".$var[$d+4]."',"."'".$var[$d+5]."',"."'".$var[$d+6]."',"."'".$var[$d+7]."',"."'".$var[$d+8]."',"."'".$var[$d+9]."',"."'".$var[$d+10]."',"."'".$var[$d+11]."')";
		}

		$dmanifest = $data->cekdata("$var[$smu]")->fetch_object();
		$mankoli = $dmanifest->sumkol;
		$mankilo = $dmanifest->sumweight;
		$dcargo = $data->cekcargo("$var[$smu]")->fetch_object();
		$ckoli = $dcargo->qty;
		$ckilo = $dcargo->kilo;

		if($mankoli < $ckoli && $mankilo < $ckilo){
			if($var[$qty] + $mankoli <= $ckoli && $var[$weight] + $mankilo <= $ckilo ){
				$insert= $data->masukan($info);
			}
		}else{
			echo "<script>alert('smu : $var[$smu]\\nstatus : full manifest ')</script>";
		}
	}

	$code_manifest = $var[0];
	$page = 1;
}

//button-page-manifest
elseif(isset($_POST['go_page'])){
	$code_manifest =  $_POST['codemanifest'];
	$page = (int)$_POST['t_page'];
}
elseif(isset($_POST['back_page'])){
	$coman =  $_POST['codemanifest'];
	$halaman = (int)$_POST['t_page'];
	if($halaman === 1){
		$code_manifest =  $coman;
		$page = $halaman;
		$_SESSION['manifest_page'] = [

			'pesan' => 'This is the first page!',
			'aksi' => 'Last'
		];
	}else{
		$code_manifest =  $coman;
		$page = $halaman-1;
	}
}
elseif(isset($_POST['next_page'])){
	$coman =  $_POST['codemanifest'];
	$halaman = (int)$_POST['t_page'];
	$last = (int)$_POST['last'];
	if($halaman === $last){
		$code_manifest =  $coman;
		$page = $last;
		$_SESSION['manifest_page'] = [

			'pesan' => 'This is the last page!',
			'aksi' => 'Last'
		];
	}else{
		$code_manifest =  $coman;
		$page = $halaman+1;
	}
}

// reprint manifest
elseif(isset($_POST['reprint'])){
	$code_manifest = $_POST['codemanifest'];
	$page = 1;
}else{
	$page = 1;
}




if($page === 1){
	$dataperpage = 25;
	$startdata = 0;
}
if($page > 1){
	$dataperpage = 25;
	$awal =25 + (($page-2)*25)+1;
	$startdata = $awal;
}
// echo $startdata." | ".$dataperpage;
// var_dump($code_manifest);
$totaldataperpage = $startdata+$dataperpage;
$takedata = $data->getall($code_manifest);
$countpage = mysqli_num_rows($takedata);

$distinctdata= $data->getdistinct($code_manifest);
if($countpage<=25){
	$tpages = 1;
}
else{
	$ss = $countpage-25;
	$tpages = ceil($ss/20)+1;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0" > -->
	<title>Print Manifest</title>
	<link rel="stylesheet" href="../assets/css/print_manifest.css" >
	<link href="../assets/fontawesome/css/all.css" rel="stylesheet" crossorigin="anonymous">
	<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" >
	
	
	
</head>
<body>
	<div class="position-relative">
		<?php 
		if(isset($_SESSION['manifest_page'])){
			echo '<div class="alert alert-warning alert-dismissible fade show position-absolute start-50 translate-middle" role="alert" style="z-index: 2; top : 5em; width : 50%;"><strong>Warning! </strong>'.$_SESSION['manifest_page']['pesan'].'.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
			unset($_SESSION['manifest_page']);
		}
		?>
		<!-- <div class="alert alert-warning alert-dismissible fade show position-absolute start-50 top-50 translate-middle" role="alert" style="z-index: 2;">
			<strong>Holy guacamole!</strong> You should check in on some of those fields below.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div> -->
	</div>
	
	<!-- As a heading -->
	<nav class="navbar navbar-dark bg-success bg-gradient position-fixed">
		<div class="container-fluid">
			<span class="navbar-brand mb-0 h1">Cetak Manifest</span>
			<div class="d-flex">
				<form action="dls_print.php?" method="post" target="_blank">
					<input type="text" value="<?php echo $code_manifest ?>" name="codemanifest" hidden >
					<button type="submit" id="nextPage" class="ms-1 me-3 btn btn-sm btn-dark" name="dls">DLS</button>
				</form>
				<form action="" method="post">
					<label for="" style="color: white; font-weight: bold">Captain</label>
					<input type="text" id="captainName" >
					<label class="ms-2" for="" style="color: white; font-weight: bold">Transite</label>
					<input type="text" id="transite" >
					<label class="ms-2" for="" style="color: white; font-weight: bold">Page</label>

					<input type="text" id="codmanifest" value="<?php echo $code_manifest ?>" name="codemanifest" hidden>
					<input type="text" id="page" style="width:2em" name="t_page" value="<?php echo $page ?>" hidden>
					<label id="totalPage" style="color: white">
						<?php echo $page ?> / <?php 	echo $tpages; ?>
					</label>
					<input type="text" id="last" style="width:2em" name="last" value="<?php echo $tpages ?>" hidden>
					<!-- <button type="submit" id="bPage" class="ms-1 me-3 btn btn-dark" name="go_page">
						Go
					</button> -->
					
					<button type="submit" id="backPage" class="ms-1 me-3 btn btn-sm btn-dark" name="back_page"><<</button>
					<button type="submit" id="nextPage" class="ms-1 me-3 btn btn-sm btn-dark" name="next_page">>></button>
					
					<button type="button" id="print" class="ms-5 me-3 btn btn-dark" onclick="cetak()">
						print
					</button>
				</form>
				
			</div>
		</div>
	</nav>
	<!-- navigation -->
	<div id="navigation-wrap" class="position-relative">
		<div id="navigation" class="navigasi-off bg-dark bg-gradient d-flex px-2">
			<i id="margin">Margin</i>
			<div class="wrap-arrow row m-0 p-0 g-0">
				<div class="arrow col-12 d-flex">
					<div class="d-button text-center">
						<button class="btn btn-sm btn-dark my-2" onclick="tambahA()">
							<i class="fa-solid fa-plus"></i></button
							><br />
							<button class="btn btn-sm btn-dark" onclick="minA()">
								<i class="fa-solid fa-minus"></i>
							</button>
						</div>
						<div
						class="d-text d-flex justify-content-center align-items-center"
						>
						<div class="text-center">
							<i class="fa-solid fa-up-down"></i><br />
							<i class="fa-solid fa-file-lines"></i>
						</div>
					</div>
				</div>
				<span class="text-center">
					<button class="btn btn-sm btn-dark mt-2" onclick="reset()">
						reset
					</button>
					<input
					type="text"
					value=""
					class="text-center"
					id="inputControl"
					disabled
					/>
					<button
					class="btn btn-sm btn-dark mt-2"
					onclick="minimize()"
					id="minButton"
					>
					min
				</button>
			</span>
			<div class="arrow col-12 d-flex">
				<div class="d-button text-center">
					<button class="btn btn-sm btn-dark my-2" onclick="tambahB()">
						<i class="fa-solid fa-plus"></i></button
						><br />
						<button class="btn btn-sm btn-dark" onclick="minB()">
							<i class="fa-solid fa-minus"></i>
						</button>
					</div>
					<div
					class="d-text d-flex justify-content-center align-items-center"
					>
					<i class="fa-solid fa-left-right"></i>&nbsp;<i
					class="fa-solid fa-file-lines"
					></i>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- print target -->
<div id="target-print" class="target">
	<div class="position-relative">
		<div class="position-absolute" style="top: -80px; left: 630px">
			<?php
			if($tpages> 1){
				echo "Page ".$page;
			}
			?>
		</div>
	</div>
	<table class="t-heigh t-width">
		<thead>
			<tr>
				<th colspan="2" class="text-end pe-2"><?php echo date('d-F-Y', strtotime($distinctdata->tanggal)) ?></th>
				<th colspan="2" class="text-end pe-1">
					<?php 
					$flt = explode('-',$distinctdata->flight_no);
					echo "PK-".$flt[0];
					?>
				</th>
				<th>
					<div class="col5 text-end pe-1"><?= $flt[0];
					 ?></div>
				</th>
				<th>
					<div class="col6"></div>
				</th>
				<th>
					<div class="col7 text-end pe-1">CGK</div>
				</th>
				<th>
					<div class="col8 text-end pe-1"><?php echo $flt[1]; ?></div>
				</th>
				<th>
					<div class="col9 text-end pe-2" id="transitan"></div>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><div class="row0"></div></td>
			</tr>
			<?php
			$datarow = 0;
			$totalkoli = 0;
			$totalkilo = 0;
			$totalkoli2 = 0;
			$totalkilo2 = 0;
			$rowsisa2 = $page>1 ? $totaldataperpage-1 : $totaldataperpage;
			while($result = $takedata->fetch_object()){
				$datarow = $datarow + 1;
				if($datarow	< $startdata){
					$totalkoli2 = $totalkoli2 + (int)$result->koli;
					$totalkilo2 = $totalkilo2 + (int)$result->weight;
				}
				if($datarow>=$startdata && $datarow<=$rowsisa2):?>
					<tr>
						<td>
							<div class="col1 text-center"><?php echo $datarow; ?></div>
						</td>
						<td>
							<div class="col2 text-center"><?php echo $result->awb_number; ?></div>
						</td>
						<td>
							<div class="col3 text-center">
								<?php 
								if($result->total_koli < $result->quantity){
									echo "$result->total_koli/$result->quantity";
								}else{
									echo $result->total_koli;
								}
								?>
							</div>
						</td>
						<td>
							<div class="col4 text-center">
								<?php 
								echo $result->total_berat;
								?>
							</div>
						</td>
						<td colspan="2">
							<div class="col11 text-center">
								<?php echo $result->comodity; ?>
							</div>
						</td>
						<td colspan="2">
							<div class="col12 text-center" style="font-size: 0.8em; font-weight: bold;">
								<?php 
								if($result->total_berat < $result->kilo){
									echo "$result->total_berat/$result->kilo (kg)";
								}else{
									echo "";
								}
								?>
							</div>
						</td>
						<td>
							<div class="col13 text-center">
								<?php echo $result->remarks; ?>
							</div>
						</td>
					</tr>
					<?php
					$totalkoli = $totalkoli + (int)$result->koli;
					$totalkilo = $totalkilo + (int)$result->weight;
					?>
				<?php endif;
			}
			$rowsisa = $page>1 ? $totaldataperpage-1 : $totaldataperpage;
			for ($i=$datarow+1; $i <= $rowsisa ; $i++) :?>
				<tr>
					<td>
						<div class="col1 text-center"><?php echo $i; ?></div>
					</td>
					<td>
						<div class="col2 text-center"></div>
					</td>
					<td>
						<div class="col3 text-center"></div>
					</td>
					<td>
						<div class="col4 text-center"></div>
					</td>
					<td colspan="2">
						<div class="col11 text-center">

						</div>
					</td>
					<td colspan="2">
						<div class="col12 text-center">

						</div>
					</td>
					<td>
						<div class="col13 text-center">

						</div>
					</td>
				</tr>

				<?php
			endfor;
			?>




			<tr>
				<td colspan="2">
					<div class="col10"></div>
				</td>
				<?php 
				if($page == $tpages){
					$tkoval =  $distinctdata->koliman;
					$tkival =  $distinctdata->kiloman;
				}
				?>	
				<td>
					<div class="col14 text-center" style="padding: 0px">
						<?php echo ($page == $tpages)?number_format($tkoval):""; ?>
					</div>
				</td>
				<td>
					<div class="col15 text-center" style="padding: 0px">
						<?php echo ($page == $tpages)?number_format($tkival):""; ?>								
					</div>
				</td>
				<td colspan="5"></td>
			</tr>
		</tbody>
	</table>
	<div class="d-flex" id name>
		<div class="kapten text-center" id="noc"></div>
		<div class="staff text-end pe-5">
			<?php 	echo ucfirst($distinctdata->creator) ?>
			<br>
			<br>
			<?php
			if($page < $tpages){
				$next = $page + 1;
				echo "Page ".$next;
			}
			?>
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

	var mTop=3.8, mLeft=0, valTop=0, valLeft=0;

	$(document).ready(function () {


	});
	// $("#print").click(function () {
	// 	$("#target-print").printThis();
	// });
	$("#margin").click(function () {
		$("#margin").hide();
		$("#navigation")
		.removeClass("navigasi-off")
		.addClass("navigasi-on py-4");
	});

	function minimize() {
		$("#navigation").addClass("navigasi-off");
		$("#navigation").removeClass("navigasi-on py-4");
		setTimeout(function(){
			$("#margin").show();

		},150);
	};

	function tambahA(){
		valTop = valTop+0.5;
		capture();
		margin();
	};

	function minA(){
		if(valTop>0){
			valTop = valTop-0.5;
			capture();
			margin();
		}
		else{
			alert("Margin top sudah mencapai nilai minimum!");
		};
	};
	function tambahB(){
		valLeft = valLeft+0.5;
		capture();
		margin();
	};

	function minB(){
		if(valLeft>0){
			valLeft = valLeft-0.5;
			capture();
			margin();
		}
		else{
			alert("Margin Left sudah mencapai nilai minimum!");
		};
	};

	function reset(){
		valTop = 0;
		valLeft = 0;
		capture();
		margin();
	};

	function capture(){
		$("#inputControl").val(valTop+"|"+valLeft);
	};

	function margin(){
		marTop = mTop + valTop;
		marLeft = mLeft + valLeft;

		var valueTop = marTop+"cm", valueLeft = marLeft+"cm";
		$("table").css("margin-top", valueTop);
		$("table").css("margin-left", valueLeft);
	};
	$("#captainName").keyup(function(){
		var kapten = $("#captainName").val();
		kapten = kapten.toLowerCase().replace(/\b[a-z]/g, function(letter) {
			return letter.toUpperCase();
		});
		$("#noc").text(kapten);
	});
	$("#transite").keyup(function(){
		var  trans = $("#transite").val();
		$("#transitan").text(trans.toUpperCase());
	});
	function cetak(){
		$("#target-print").printArea();
	}
</script>
</body>
</html>
