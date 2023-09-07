<?php
if($_SESSION['hak_akses'] !== "supervisor"){
	echo "<script>window.location.href = '?page=home'</script>";
}
include('models/m_report.php');
include('models/m_airline.php');
$report  = new Report($connection);
$m_airline = new Airline($connection);
$d_airline = $m_airline->call_all_airline();

?>
<div class = "kontener2 px-5 py-4">
	<nav style="--bs-breadcrumb-divider: '》';" aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item ps-4" aria-current="page"><i class="fa-solid fa-database"></i> Report
			</li>
			<li class="breadcrumb-item ps-4 active" aria-current="page">All Tonage</li>
		</ol>
	</nav>
	<div class = "content p-4" style="font-family: roboto;">
		<h6><i class="fas fa-newspaper"></i> All Tonnage</h6>
		<form action="" method="post">
			<div class="row g-0 p-0 m-0">
				<div class = "description col-sm-2 px-3">
					<h5>Tonnage</h5>
					<p>View all report data outgoing </p>
				</div>
				<div class = "main-form col-sm-10 px-3 ">
					<div class="mb-1">
						<div class="row">
							<div class="col-sm-3 mb-2">
								<label for="">Select Airline:</label>
								<select class="select2 form-select form-select-sm" name="airline" id="airline" data-placeholder="Select Airline">
									<option></option>
									<option value="all">All Airline</option>
									<?php 
									while ($airline = $d_airline->fetch_object()) {
										?>
										<option value="<?= $airline->airline_name ?>"><?= $airline->airline_name?></option>
										<?php
									}
									?>
								</select>                
							</div>
						</div>
					</div>
					<div class="mb-3">
						<div class="" 	 id="manifest">
							<div class="row g-0 p-0 m-0">
								<div class="position-relative col-md-2 mt-2 me-2">
									<input class="form-control form-control-sm" type="text" id="tanggalAwal" autocomplete="off" name="date1">
									<label>Tanggal awal</label>
								</div>
								<div class="position-relative col-md-2 mt-2 me-2">
									<input class="form-control form-control-sm" type="text" id="tanggalAkhir" autocomplete="off" name="date2">
									<label>Tanggal Akhir</label>
								</div>
								<div class="col-2 mt-2">
									<button type="submit" class="btn btn-sm" name="search">search</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		<?php 
		if(isset($_POST['search'])): ?>
			<div class="mt-5" id="contentAllReport">
				<div class="d-flex mb-3">
					<div class="d-flex">
						<table class="report-result">
							<tbody>
								<tr>
									<td class="head">Table</td>
									<td class="value">Tonnage Outgoing</td>
								</tr>
								<tr>
									<td class="head">Total quantity</td>
									<td class="value" id="quantity">value</td>
								</tr>
								<tr>
									<td class="head">Total Weight</td>
									<td class="value" id="weight">value</td>
								</tr>
								<tr>
									<td class="head">Total Volume</td>
									<td class="value" id="volume">value</td>
								</tr>
								<tr>
									<td class="head">Total Nett</td>
									<td class="value" id="net">value</td>
								</tr>
								<tr>
									<td class="head">Total Admin</td>
									<td class="value" id="admin">value</td>
								</tr>
								<tr>
									<td class="head">Total sewa gudang</td>
									<td class="value" id="sg">value</td>
								</tr>
								<tr>
									<td class="head">Total kade</td>
									<td class="value" id="kade">value</td>
								</tr>
								<tr>
									<td class="head">Total AP2</td>
									<td class="value" id="ap2">value</td>
								</tr>
								<tr>
									<td class="head">Total airport tax</td>
									<td class="value" id="airtax">value</td>
								</tr>
								<tr>
									<td class="head">Total ppn</td>
									<td class="value" id="ppn">value</td>
								</tr>
								<tr>
									<td class="head" >Total Materai</td>
									<td class="value" id="materai">value</td>
								</tr>
								<tr>
									<td class="head">Grand Total</td>
									<td class="value" id="gTotal">value</td>
								</tr>
							</tbody>
						</table>
						<table class="ms-4 report-result">
							<tbody>
								<tr>
									<td class="head">Table</td>
									<td class="value">Tonnage Void</td>
								</tr>
								<tr>
									<td class="head">Total quantity</td>
									<td class="value" id="quantityvoid">value</td>
								</tr>
								<tr>
									<td class="head">Total Weight</td>
									<td class="value" id="weightvoid">value</td>
								</tr>
								<tr>
									<td class="head">Total Volume</td>
									<td class="value" id="volumevoid">value</td>
								</tr>
								<tr>
									<td class="head">Total Nett</td>
									<td class="value" id="netvoid">value</td>
								</tr>
								<tr>
									<td class="head">Total Admin</td>
									<td class="value" id="adminvoid">value</td>
								</tr>
								<tr>
									<td class="head">Total sewa gudang</td>
									<td class="value" id="sgvoid">value</td>
								</tr>
								<tr>
									<td class="head">Total kade</td>
									<td class="value" id="kadevoid">value</td>
								</tr>
								<tr>
									<td class="head">Total AP2</td>
									<td class="value" id="ap2void">value</td>
								</tr>
								<tr>
									<td class="head">Total airport tax</td>
									<td class="value" id="airtaxvoid">value</td>
								</tr>
								<tr>
									<td class="head">Total ppn</td>
									<td class="value" id="ppnvoid">value</td>
								</tr>
								<tr>
									<td class="head">Total Materai</td>
									<td class="value" id="materaivoid">value</td>
								</tr>
								<tr>
									<td class="head">Grand Total</td>
									<td class="value" id="gTotalvoid">value</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div>
						<form action="views/report_excel.php" method="post" target="_blank">
							<?php 
							if(@$_POST['airline']){
								if($_POST['airline'] == 'all'){
									$t_airline = 'all';
								}else{
									$t_airline = $_POST['airline'];
								}
							}else{
								$t_airline = 'all';
							}
							?>
							<input type="text" name="tanggal_awal" value="<?php echo $_POST['date1'] ?>" hidden>
							<input type="text" name="tanggal_akhir" value="<?php echo $_POST['date2'] ?>" hidden>
							<input type="text" name="airline" value="<?php echo $t_airline; ?>" hidden>
							<button class="btn btn-sm btn-success ms-4" type="submit" name="unduh">unduh Excel</button>
						</form>
					</div>
				</div>
				<table class="mt-5" id="allReportOutgoing">
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
						$date1 = $_POST['date1'];
						$date2 = $_POST['date2'];
						if(@$_POST['airline']){
							if($_POST['airline'] == 'all'){
								$takedata = $report->allbydate($date1, $date2, "complete");
							}else{
								$takedata = $report->allbydate_airline($date1, $date2, "complete", $_POST['airline']);
							}
						}else{
							$takedata = $report->allbydate($date1, $date2, "complete");
						}
						$angka = 0;

						$quantity = 0;
						$weight = 0;
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
						while($result = $takedata->fetch_object()): ?>
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
								<td class="text-center px-3"><?php echo $result->volume ?></td>
								<td class="text-center px-3">
									<?php 
									$a=$result->weight;
									$b=$result->volume;
									if($a <= 10 && $b <= 10){
										$nett = 10;
									}
									elseif($a > 10 && $a >= $b){
										$nett = $a;
									}
									elseif($b > 10 && $b>$a){
										$nett = $b;
									}
									echo $nett;
									?>
								</td> 
								<td class="text-center px-3"><?php echo number_format($result->admin) ?></td>
								<td class="text-center px-3"><?php echo number_format($result->sewa_gudang) ?></td>
								<td class="text-center px-3"><?php echo number_format($result->kade) ?></td>
								<td class="text-center px-3"><?php echo number_format($result->pjkp2u) ?></td>
								<td class="text-center px-3"><?php echo number_format($result->airport_tax) ?></td>
								<td class="text-center px-3"><?php echo number_format($result->ppn) ?></td>
								<td class="text-center px-3"><?php echo number_format($result->materai) ?></td>
								<td class="text-center px-3"><?php echo number_format($result->total) ?></td>
								<td class="text-center px-3"><?php echo $result->session ?></td>
								<td class="text-center px-3"><?php echo $result->proses_btb ?></td>
								<td class="text-center px-3"><?php echo $result->session_kasir ?></td>
								<td class="text-center px-3"><?php echo $result->proses_njg ?></td>
							</tr>
							<?php 
							$quantity = $quantity + $result->quantity;
							$weight = $weight + $result->weight;
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

					</tbody>
				</table>

				<div class="mt-5">
					Void table
				</div>
				<table class="" id="allReportOutgoingVoid">
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

						if(@$_POST['airline']){
							if($_POST['airline'] == 'all'){
								$takedatavoid = $report->allbydate($date1, $date2, "void");
							}else{
								$takedatavoid = $report->allbydate_airline($date1, $date2, "void", $_POST['airline']);
							}
						}else{
							$takedatavoid = $report->allbydate($date1, $date2, "void");
						}						
						$nomor = 0;

						$quantityvoid = 0;
						$weightvoid = 0;
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
						while($resultvoid = $takedatavoid->fetch_object()): ?>
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
								<td class="text-center px-3"><?php echo $resultvoid->volume ?></td>
								<td class="text-center px-3">
									<?php 
									$c=$resultvoid->weight;
									$d=$resultvoid->volume;
									if($c <= 10 && $d <= 10){
										$nettvoid = 10;
									}
									elseif($c > 10 && $c >= $d){
										$nettvoid = $c;
									}
									elseif($d > 10 && $d>$c){
										$nettvoid = $d;
									}
									echo $nettvoid;
									?>
								</td> 
								<td class="text-center px-3"><?php echo number_format($resultvoid->admin) ?></td>
								<td class="text-center px-3"><?php echo number_format($resultvoid->sewa_gudang) ?></td>
								<td class="text-center px-3"><?php echo number_format($resultvoid->kade) ?></td>
								<td class="text-center px-3"><?php echo number_format($resultvoid->pjkp2u) ?></td>
								<td class="text-center px-3"><?php echo number_format($resultvoid->airport_tax) ?></td>
								<td class="text-center px-3"><?php echo number_format($resultvoid->ppn) ?></td>
								<td class="text-center px-3"><?php echo number_format($resultvoid->materai) ?></td>
								<td class="text-center px-3"><?php echo number_format($resultvoid->total) ?></td>
								<td class="text-center px-3"><?php echo $resultvoid->session ?></td>
								<td class="text-center px-3"><?php echo $resultvoid->proses_btb ?></td>
								<td class="text-center px-3"><?php echo $resultvoid->session_kasir ?></td>
								<td class="text-center px-3"><?php echo $resultvoid->proses_njg ?></td>
								<td class="text-center px-3"><?php echo $resultvoid->keterangan ?></td>
							</tr>
							<?php 

							$quantityvoid = $quantityvoid + $resultvoid->quantity;
							$weightvoid = $weightvoid + $resultvoid->weight;
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

					</tbody>
				</table>
			</div>

			<script>
				$(document).ready(function(){

					


					var quantity = <?php echo $quantity ?>;
					var weight = <?php echo $weight ?>;
					var volume = <?php echo $volume ?>;
					var net = <?php echo $net ?>;
					var admin = <?php echo $admin ?>;
					var sg = <?php echo $sg ?>;
					var kade = <?php echo $kade ?>;
					var ap2 = <?php echo $ap2 ?>;
					var airtax = <?php echo $airtax ?>;
					var ppn = <?php echo $ppn ?>;
					var materai = <?php echo $materai ?>;
					var g_total = <?php echo $g_total ?>;

					$("#quantity").text(quantity.toLocaleString()+" pcs");
					$("#weight").text(weight.toLocaleString()+" kg");
					$("#volume").text(volume.toLocaleString()+" kg");
					$("#net").text(net.toLocaleString() +" kg");
					$("#admin").text("Rp "+admin.toLocaleString());
					$("#sg").text("Rp "+sg.toLocaleString());
					$("#kade").text("Rp "+kade.toLocaleString());
					$("#ap2").text("Rp "+ap2.toLocaleString());
					$("#airtax").text("Rp "+airtax.toLocaleString());
					$("#ppn").text("Rp "+ppn.toLocaleString());
					$("#materai").text("Rp "+materai.toLocaleString());
					$("#gTotal").text("Rp "+g_total.toLocaleString());

					var quantityvoid = <?php echo $quantityvoid ?>;
					var weightvoid = <?php echo $weightvoid ?>;
					var volumevoid = <?php echo $volumevoid ?>;
					var netvoid = <?php echo $netvoid ?>;
					var adminvoid = <?php echo $adminvoid ?>;
					var sgvoid = <?php echo $sgvoid ?>;
					var kadevoid = <?php echo $kadevoid ?>;
					var ap2void = <?php echo $ap2void ?>;
					var airtaxvoid = <?php echo $airtaxvoid ?>;
					var ppnvoid = <?php echo $ppnvoid ?>;
					var materaivoid = <?php echo $materaivoid ?>;
					var g_totalvoid = <?php echo $g_totalvoid ?>;

					$("#quantityvoid").text(quantityvoid.toLocaleString()+" pcs");
					$("#weightvoid").text(weightvoid.toLocaleString()+" kg");
					$("#volumevoid").text(volumevoid.toLocaleString()+" kg");
					$("#netvoid").text(netvoid.toLocaleString()+" kg");
					$("#adminvoid").text("Rp "+adminvoid.toLocaleString());
					$("#sgvoid").text("Rp "+sgvoid.toLocaleString());
					$("#kadevoid").text("Rp "+kadevoid.toLocaleString());
					$("#ap2void").text("Rp "+ap2void.toLocaleString());
					$("#airtaxvoid").text("Rp "+airtaxvoid.toLocaleString());
					$("#ppnvoid").text("Rp "+ppnvoid.toLocaleString());
					$("#materaivoid").text("Rp "+materaivoid.toLocaleString());
					$("#gTotalvoid").text("Rp "+g_totalvoid.toLocaleString());
				});
			</script>	
		<?php endif;
		?>

	</div>
</div>
<script src="assets/select2/select2.min.js"></script>

<script>
	$(document).ready(function(){
		$(".select2").select2({
			placeholder: $(this).data('placeholder'),
		});
	});
	
	$("#tanggalAwal").datetimepicker({
		format: 'Y-m-d H:i:s',
		formatDate: 'Y-m-d',
		formatTime: 'H:i:s',
		step:30
	});
	$("#tanggalAkhir").datetimepicker({
		format: 'Y-m-d H:i:s',
		formatDate: 'Y-m-d',
		formatTime: 'H:i:s',
		step:30
	});
</script>