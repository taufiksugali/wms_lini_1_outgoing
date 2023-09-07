<?php 
include('models/m_btb.php');
$data = new Btb($connection);


?>
<div class = "kontener2 px-5 py-4">
	<nav style="--bs-breadcrumb-divider: '》';" aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item ps-4" aria-current="page"><i class="fa-solid fa-database"></i> Report
			</li>
			<li class="breadcrumb-item ps-4 active" aria-current="page">Cargo details</li>
		</ol>
	</nav>
	<div class = "content p-4" style="font-family: roboto;">
		<h6><i class="fas fa-newspaper"></i> Cargo Details</h6>
		<form action="" method="post">
			<div class="row g-0 p-0 m-0">
				<div class = "description col-sm-2 px-3">
					<h5>Details</h5>
					<p>Search smu number to view details or edit </p>
				</div>
				<div class = "main-form col-sm-10 px-3 ">
					<div class="mb-3">
						<div class="" 	 id="manifest">										
							<div class="row g-0 p-0 m-0">
								<div class="position-relative col-md-2 mt-2 me-2">
									<input list="datalist" class="form-control form-control-sm" type="text" placeholder="" autocomplete="off" id="theTarget" name="the_target">
									
									<datalist id="datalist">
										<?php 
										$cari = $data->carismu();
										while($result = $cari->fetch_object()) :?>
											<option value="<?php echo $result->smu; ?>"></option>
										<?php endwhile; ?>
									</datalist>
									<label>SMU</label>
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
		if(isset($_POST['search'])):?>
			<?php 
			$target = $_POST['the_target'];
			$cari = $data->carialldata($target);
			$result = $cari->fetch_object();
			if(!$result){?>
				No data Found !

				<?php 
			}
			else{
				?>		
				<div class="mt-5 " id="cargoDetails">
					<div class="deskripsi position-relative pt-3 ps-2">
						<div class="d-flex position-absolute" style="background-color: transparent;">
							<h6 class="px-2 " id="menDesc">Description</h6>
							<h6 class="px-2 " id="menMas">Manifest</h6>
							<h6 class="px-2 " id="menPay">Payment</h6>
						</div>
						<div class="pt-3" id="contentDesc">
							<div class="p-2">
								<div class="d-flex">
									<div style="width: 10em;">SMU</div>
									<div>
										: <?php echo $result->smu ?>
										<?php
										$hakakses = $_SESSION['hak_akses'];
										if ($hakakses == "kasir" || $hakakses == "supervisor"){
											$linkkasir = "?page=finance-report&session=".$result->session_kasir."&smu=".$result->smu;
											$link = "#";
											$act1 = "return alert('Ask acecptance to revision !')";
											$act2 = "";
										}elseif ($hakakses == "acceptance" || $hakakses == "pic"){
											$link = "?page=session-report&session=".$result->session_kasir."&smu=".$result->smu;
											$linkkasir = "#";											
											$act1 = "";
											$act2 = "return alert('Ask chasier to void !')";
										}
										if($result->status == "complete"){ ?>
											<a href="<?php echo $linkkasir ?>" style="text-decoration: none; color: darkred; font-size:0.7rem; font-weight:  bold;" onclick="<?php echo $act2 ?>">Void</a>
										<?php }
										elseif($result->status == "proced" || $result->status == "revisi"){?>
											<a href="<?php echo $link ?>" style="text-decoration: none; color: darkred; font-size:0.7rem; font-weight:  bold;" onclick="<?php echo $act1 ?>">revisi</a>
										<?php }?>
									</div>
								</div>
								<div class="d-flex">
									<div style="width: 10em;">Agent</div>
									<div>: <?php echo $result->agent_name ?></div>
								</div>
								<div class="d-flex">
									<div style="width: 10em;">Shipper</div>
									<div>: <?php echo $result->shipper_name ?></div>
								</div>
								<div class="d-flex">
									<div style="width: 10em;">PIC</div>
									<div>: <?php echo $result->pic ?></div>
								</div>
								<div class="d-flex">
									<div style="width: 10em;">Quantity</div>
									<div>: <?php echo number_format($result->quantity) ?> coly</div>
								</div>
								<div class="d-flex">
									<div style="width: 10em;">Weight</div>
									<div>: <?php echo number_format($result->weight) ?> Kg</div>
								</div>
								<div class="d-flex">
									<div style="width: 10em;">Volume</div>
									<div>: <?php echo number_format($result->volume) ?> Kg</div>
								</div>
								<div class="d-flex">
									<div style="width: 10em;">Nett</div>
									<div>
										<?php 
										if($result->weight >= $result->volume && $result->weight > 10){
											$nett = $result->weight;
										}elseif($result->volume >= $result->weight && $result->volume > 10){
											$nett = $result->volume;
										}else{
											$nett = 10;
										}
										?>
										: <?php echo number_format($nett) ?> Kg									
									</div>
								</div>
								<div class="d-flex">
									<div style="width: 10em;">Flight</div>
									<div>: <?php echo $result->flight_no ?></div>
								</div>
								<div class="d-flex">
									<div style="width: 10em;">Destination</div>
									<div>: <?php echo $result->destination ?></div>
								</div>	
								<div class="d-flex">
									<div style="width: 10em;">Date of btb</div>
									<div>: <?php echo date("d F Y", strtotime($result->ctimestamp)) ?></div>
								</div>	
								<div class="d-flex">
									<div style="width: 10em;">Btb number</div>
									<div>: <?php echo $result->no_do ?></div>
								</div>	
								<div class="d-flex">
									<div style="width: 10em;">Btb by</div>
									<div>: <?php echo $result->proses_by ?></div>
								</div>	
								<div class="d-flex">
									<div style="width: 10em;">Btb session</div>
									<div>: <a href=""><?php echo $result->session ?></a></div>
								</div>
								<div class="d-flex justify-content-end me-4">
									<a href="models/p_btb.php?data=<?php echo $result->smu; ?>
									&print=reprint">
									<button class="btn btn-sm btn-outline-primary">reprint btb</button>
								</a>
							</div>	
						</div>
					</div>
					<div class="pt-3" id="contentMas">
						<div class="p-2">
							<div class="d-flex">
								<div style="width: 10em;">Manifested by flight</div>
								<div>: <a href=""><?php echo $result->man_flight ?></a></div>
							</div>
							<div class="d-flex">
								<div style="width: 10em;">Date of Manifest</div>
								<div>: <?php echo $tanggal = $result->man_date===null ? "":date("d F Y", strtotime($result->man_date)); ?></div>
							</div>	
							<div class="d-flex">
								<div style="width: 10em;">Manifest by</div>
								<div>: <?php echo $result->creator ?></div>
							</div>
							<div class="d-flex justify-content-end me-4">
								<button class="btn btn-sm btn-outline-primary" onclick="cetak('#contentMas')">print data</button>
							</div>		
						</div>
					</div>
					<div class="pt-3" id="contentPay">
						<div class="p-2">
							<div class="d-flex">
								<div style="width: 10em;">Payment status</div>
								<div>: <?php echo $pay = $result->stimestamp===null ? "Unpaid" : "Paid" ?></div>
							</div>
							<div class="d-flex">
								<div style="width: 10em;">Date of payment</div>
								<div>: <?php echo  $date = $result->stimestamp===null ? "": date("d F Y", strtotime($result->stimestamp)) ?></div>
							</div>	
							<div class="d-flex">
								<div style="width: 10em;">Time of payment</div>
								<div>: <?php echo $time = $result->stimestamp===null ? "":date("H:i:s",strtotime($result->stimestamp)) ?></div>
							</div>	
							<div class="d-flex">
								<div style="width: 10em;">Session</div>
								<div>: <?php echo $result->session_kasir ?></div>
							</div>
							<div class="d-flex">
								<div style="width: 10em;">Chasier</div>
								<div>: <?php echo $result->proses_kasir ?></div>
							</div>
							<div class="d-flex">
								<div style="width: 10em;">Njg</div>
								<div>: <?php echo $result->njg ?></div>
							</div>
							<div class="d-flex">
								<div style="width: 10em;">Admin</div>
								<div>: Rp. <?php echo number_format($result->admin) ?></div>
							</div>
							<div class="d-flex">
								<div style="width: 10em;">Sewa gudang</div>
								<div>: Rp. <?php echo number_format($result->sewa_gudang) ?></div>
							</div>
							<div class="d-flex">
								<div style="width: 10em;">Kade</div>
								<div>: Rp. <?php echo number_format($result->kade) ?></div>
							</div>
							<div class="d-flex">
								<div style="width: 10em;">Pjkp2u</div>
								<div>: Rp. <?php echo number_format($result->pjkp2u) ?></div>
							</div>
							<div class="d-flex">
								<div style="width: 10em;">Airport Surcharge</div>
								<div>: Rp. <?php echo number_format($result->airport_tax) ?></div>
							</div>
							<div class="d-flex">
								<div style="width: 10em;">Ppn</div>
								<div>: Rp. <?php echo number_format($result->ppn) ?></div>
							</div>
							<div class="d-flex">
								<div style="width: 10em;">Materai</div>
								<div>: Rp. <?php echo number_format($result->materai) ?></div>
							</div>
							<div class="d-flex">
								<div style="width: 10em;">Total amount</div>
								<div>: Rp. <?php echo number_format($result->total) ?></div>
							</div>
							<div class="d-flex justify-content-end me-4">
								<button class="btn btn-sm btn-outline-primary" onclick="cetak('#contentPay')">print data</button>
							</div>		
						</div>
					</div>
				</div>
			</div>


		<?php }; ?>

	<?php endif; ?>

</div>


<script src="assets/printThis/printThis.js"></script>

<script>
	function cetak(param){
		$("#cargoDetails button").hide();
		$(param).printThis();
		const back = setTimeout(tombol, 5000);

		function tombol(){
			$("#cargoDetails button").show();
		}
	};
	$(document).ready(function(){
		$("#loading").css("display","none");

		$("#tAwb").hide();
		$("#tDate").hide();
		$("#menDesc").addClass("aktif");
		$("#menMas").addClass("normal");
		$("#menPay").addClass("normal");
		$("#contentMas").hide();
		$("#contentPay").hide();

	})
</script>

<script>
	$("#menDesc").click(function(){
		$("#menDesc").addClass("aktif");
		$("#menMas").addClass("normal");
		$("#menPay").addClass("normal");
		$("#menDesc").removeClass("normal");
		$("#menMas").removeClass("aktif");
		$("#menPay").removeClass("aktif");

		$("#contentDesc").show();
		$("#contentMas").hide();
		$("#contentPay").hide();

	});

	$("#menMas").click(function(){
		$("#menDesc").addClass("normal");
		$("#menMas").addClass("aktif");
		$("#menPay").addClass("normal");
		$("#menDesc").removeClass("aktif");
		$("#menMas").removeClass("normal");
		$("#menPay").removeClass("aktif");

		$("#contentDesc").hide();
		$("#contentMas").show();
		$("#contentPay").hide();

	});

	$("#menPay").click(function(){
		$("#menDesc").addClass("normal");
		$("#menMas").addClass("normal");
		$("#menPay").addClass("aktif");
		$("#menDesc").removeClass("aktif");
		$("#menMas").removeClass("aktif");
		$("#menPay").removeClass("normal");

		$("#contentDesc").hide();
		$("#contentMas").hide();
		$("#contentPay").show();

	});
</script>