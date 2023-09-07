<?php
if($_SESSION['hak_akses'] !== "supervisor"){
	echo "<script>window.location.href = '?page=home'</script>";
	exit;
}
include('models/m_report.php');
$report  = new Report($connection);



if(isset($_POST['go'])){
	$bulan = $_POST['bulan'];
	$tanggal = date('F Y', strtotime($bulan));
	$hari = date('N',strtotime('01 '.$tanggal));
}elseif(isset($_POST['previous'])){
	$bulan = $_POST['bulan'];
	$m_1  = mktime(0,0,0,date("n", strtotime($bulan)),0,date("Y", strtotime($bulan)));
	$tanggal = date("F Y", $m_1);
	$hari = date('N',strtotime('01 '.$tanggal));
}
elseif(isset($_POST['next'])){
	$bulan = $_POST['bulan'];
	$p_1  = mktime(0,0,0,date("n", strtotime($bulan))+2,0,date("Y", strtotime($bulan)));
	$tanggal = date("F Y", $p_1);
	$hari = date('N',strtotime('01 '.$tanggal));
}
else{
	$tanggal = date('F Y');
	$hari = date('N',strtotime('01 '.$tanggal));
}
$kalender = CAL_GREGORIAN;
$total_hari = cal_days_in_month($kalender, date('m',strtotime($tanggal)), date('Y', strtotime($tanggal)));
?>
<div class = "kontener2 px-5 py-4">
	<nav style="--bs-breadcrumb-divider: '》';" aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item ps-4" aria-current="page"><i class="fa-solid fa-database"></i> Report
			</li>
			<li class="breadcrumb-item ps-4 active" aria-current="page">Calendar Tonnage</li>
		</ol>
	</nav>
	<div class = "content p-4" style="font-family: roboto;">
		<h6><i class="fas fa-calendar-alt"></i> Calendar of Tonnage</h6>
		
		<div class="row g-0 p-0 m-0">
			<div class = "description col-lg-2 px-3">
				<h5>Calendar</h5>
				<p>View report data directly by calendar. *Note: <i>the ammount of money maybe not same like actual report, you need to chose and click to view the details</i> </p>
			</div>
			<div class = "main-form col-lg-10 px-3 " style="overflow: auto;">

				<form action="" method="post">
					<div class="my-3 px-2 d-flex justify-content-between align-items-center" >
						<button type="submit" class="btn btn-sm btn-ungu px-2" name="previous" style="max-height: 2rem;"><<</button>
						<div class="row gx-5 mx-2">
							<input class="col-lg-8" type="month" name="bulan" value="<?php echo date('Y-m', strtotime($tanggal)); ?>">
							<div class="col-lg-1" style="min-height: 10px;" ></div>
							<button type="submit" class="col-lg-2 btn btn-sm btn-ungu" name="go">Go</button>
						</div>
						<button type="submit" class="btn btn-sm btn-ungu px-2" name="next" style="max-height: 2rem;">>></button>
					</div>
				</form>
				<div class="d-flex justify-content-start px-3" style="overflow: auto;">

					<table class="t-tanggal " style="min-width: 100%;">
						<thead>
							<tr>
								<th>Sunday</th>
								<th>Monday</th>
								<th>Tuesday</th>
								<th>Wednesday</th>
								<th>Thursday</th>
								<th>Friday</th>
								<th>Saturday</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$month = date('Y-m', strtotime($tanggal));
							$cari = $report->allbymonth($month);

							$minggu = round($total_hari/7);
							$box= 0;
							$offset = $hari < 7 ? $hari : 0;
							while(7*$minggu < $offset + $total_hari){
								$minggu++;
							}
							$week = 0;
							$tanggalan=0;
							for($i=1; $i <= $minggu; $i++):?>
								<?php $week = $week + 7; ?>
								<tr>
									<?php 
									if($hari<7){
										for($a=1; $a<=$hari;$a++):
											?>
											<?php $box= $box+1; ?>
											<td>
												<div class="tgl-td">
												</div>
											</td>
											<?php 

										endfor;
										$hari=0;
										while($box<$week):?>
											<?php $box = $box +1; ?>
											<td style="height: 5em; overflow: auto;">
												<div class="tgl-td">
													<?php 
													$tanggalan= $tanggalan+1; 
													if($tanggalan <= $total_hari){
														echo $d_date=$tanggalan;
														$status ="go";
													}
													if($tanggalan > $total_hari){
														$status ="stop";
													}
													?>
												</div>
												<?php 
												if($status == "go"){
													$value = $d_date." ".$tanggal;
													$value = date('Y-m-d', strtotime($value));
													$value2 = date('dmy', strtotime($value));
													// $data = $report->allbymonth($value);
													$data = $report->allbymonth_ses($value2);
											// echo $data->fetch_object()->uang;
													$nilai = 0;
													while($result = $data->fetch_object()):?>
														<?php $nilai++; ?>
														<a href="views/calendar_to_excel.php?data=<?php echo $result->session_kasir; ?>">
															<li class="li-<?php echo $nilai; ?>">
																<?php echo $result->proses_by ?> <br><?php echo $result->session_kasir ?><br><?php echo "Rp ".number_format($result->uang)." (gross)" ?>
															</li>
														</a>
														<?php 
													endwhile;
												}
												?>

											</td>
											<?php 
										endwhile;
									}
									else{
										while($box<$week):?>
											<?php $box = $box +1; ?>
											<td style="height: 5em; overflow: auto;">
												<div class="tgl-td">
													<?php 
													$tanggalan= $tanggalan+1; 
													if($tanggalan <= $total_hari){
														echo $d_date=$tanggalan;
														$status ="go";
													}
													if($tanggalan > $total_hari){
														$status ="stop";
													}
													?>
												</div>
												<?php 
												if($status == "go"){
													$value = $d_date." ".$tanggal;
													$value = date('Y-m-d', strtotime($value));
													$data = $report->allbymonth($value);
											// echo $data->fetch_object()->uang;
													$nilai = 0;
													while($result = $data->fetch_object()):?>
														<?php $nilai++; ?>
														<a href="views/calendar_to_excel.php?data=<?php echo $result->session_kasir ?>" target="_blank">
															<li class="li-<?php echo $nilai; ?>">
																<?php echo $result->proses_by ?> <br><?php echo $result->session_kasir ?><br><?php echo "Rp ".number_format($result->uang)." (gross)" ?>
															</li>
														</a>
														<?php 
													endwhile;
												}
												?>
											</td>
											<?php 
										endwhile;
									}
									?>
								</tr>
							<?php endfor;
							?>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>