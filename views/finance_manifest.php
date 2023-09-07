<?php 
require_once('models/m_manifest.php');
date_default_timezone_set('Asia/Jakarta');
$manifest = new Manifest($connection);
if(isset($_POST['delete'])){
	$del_flight = $_POST['codemanifest'];
	$delete = $manifest->deletemanifest($del_flight);
	if(!$delete){
		echo "<script>alert('Manifest gagal untuk dibatalkan')</script>";
	}else{
		echo "<script>alert('Manifest untuk flight $del_flight berhasil dibatalkan')</script>";
	}
}

if(isset($_POST['save'])){
	$code_manifest = $_POST['code_man'];
	$databaru = $_POST['text_area'];
	$databaru == substr($databaru,0,-1);
	$delete = $manifest->deletemanifest($code_manifest);
	$var = explode(",",$databaru);
	$tvar =  count($var);
	if(!$delete){
		echo "<script>alert('Manifest gagal untuk diubah')</script>";
	}else{
		for ($i=0; $i < ($tvar-1); $i = $i + 10) { 
			$tstring = $i+10;
			$smu = $i+1;
			$qty = $i+2;
			$weight = $i+3;
			for ($j=0; $j < $tstring ; $j++) { 
				$info = "('".$var[$i]."',"."'".$var[$smu]."',"."'".$var[$qty]."',"."'".$var[$weight]."',"."'".$var[$i+4]."',"."'".$var[$i+5]."',"."'".$var[$i+6]."',"."'".$var[$i+7]."',"."'".$var[$i+8]."',"."'".$var[$i+9]."')"; 
			}

			$dmanifest = $manifest->cekdata("$var[$smu]")->fetch_object();
			$mankoli = $dmanifest->sumkol;
			$mankilo = $dmanifest->sumweight;
			$dcargo = $manifest->cekcargo("$var[$smu]")->fetch_object();
			$ckoli = $dcargo->qty;
			$ckilo = $dcargo->kilo;
			// $insert= $manifest->masukan($info);
			// echo $mankoli." | ".$mankilo."&&".$ckoli." | ".$ckilo."<br>";
			if($mankoli < $ckoli && $mankilo < $ckilo){
				if($var[$qty] + $mankoli <= $ckoli && $var[$weight] + $mankilo <= $ckilo ){
					$insert= $manifest->masukan($info);
				}
				else{
					echo "<script>alert('smu : $var[$smu]\\nstatus : full manifest<over weight>\\nData dihilangkan ! ')</script>";
				}
			}else{
				echo "<script>alert('smu : $var[$smu]\\nstatus : full manifest<over quantity>\\nData dihilangkan ! ')</script>";
			}
		}
	}
}
?>

<div class = "kontener2 px-5 py-4">
	<nav style="--bs-breadcrumb-divider: '》';" aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item ps-4" aria-current="page"><i class="fa-solid fa-file-lines"></i> Manifest
			</li>
			<li class="breadcrumb-item ps-4 active" aria-current="page">View Manifest</li>
		</ol>
	</nav>
	<div class = "content p-4" style="font-family: roboto;">
		<h6><i class="fas fa-newspaper"></i> View manifest</h6>
		<form action="" method="post">
			<div class="row g-0 p-0 m-0">
				<div class = "description col-sm-2 px-3">
					<h5>Manifest</h5>
					<p>Check the manifest that has been made </p>
				</div>
				<div class = "main-form col-sm-10 px-3 ">
					<div class="mb-3">
						<div class="" 	 id="manifest">
							<div class="row g-0 p-0 m-0">
								<div class="position-relative col-md-2 mt-2 me-2">
									<input class="form-control form-control-sm" type="date" placeholder="" name="date" value="<?php echo $cetak= isset($_POST['search']) ? $_POST['date'] : date('d-m-Y'); ?>">
									<label>date</label>
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
		if(isset($_POST['search'])){
			$date = $_POST['date'];
			?>
			<div class="mt-5">
				<table class="table table-hover table-striped" id="theTable">
					<thead>
						<tr>
							<th class="text-center">No</th>
							<th class="text-center">Manifest ID</th>
							<th class="text-center">Flight</th>
							<th class="text-center">Date time</th>
							<th class="text-center">creator</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$get = $manifest->calldate($date);
						$nomor=0;
						while ($result = $get->fetch_object()) {
							$nomor++;?>
							<tr>
								<td class="text-center"><?php echo $nomor; ?></td>
								<td class="text-center"><?php echo $result->man_code; ?></td>
								<td class="text-center"><?php echo $result->flight_no; ?></td>
								<td class="text-center"><?php echo $result->tanggal; ?></td>
								<td class="text-center"><?php echo $result->creator; ?></td>
								<td class="text-center d-flex justify-content-center">
									<form action="" method="post">
										<button class="btn btn-sm btn-outline-danger" name="delete" onclick="return confirm('Anda yakin ingin membatalkan manifest ini?')">delete</button>
										<input type="text" name="codemanifest" value="<?php echo $result->man_code ?>" hidden>
									</form>

									<form action="views/print_manifest.php" method="post" target="_blank">
										<input type="text" name="codemanifest" value="<?php echo $result->man_code; ?>" hidden>
										<button class="btn btn-sm btn-outline-success ms-2" name="reprint">print</button>
										<button type="button" class="btn btn-sm btn-outline-primary ms-2 jakamania" data-bs-toggle="modal" data-bs-target="#modalEditManifest" data-manid="<?php echo $result->man_code; ?>" data-flight="<?php echo $result->flight_no; ?>">Edit</button>
									</form>
								</td>
							</tr>
						<?php };?>

					</tbody>
				</table>
			</div>
		<?php };?>		
	</div>
</div>


<!-- modal edit manifest -->
<form action="" method="post">
	<div class="modal fade" id="modalEditManifest" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog thedialog">
			<div class="modal-content themodal">
				<div class="modal-header theheader">
					<h5 class="modal-title" id="exampleModalLabel">Edit manifest</h5>
					<input type="text" id="target" hidden>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body" id="modalBody">

				</div>
				<div class="modal-footer">
					<textarea cols="30" rows="10" id="textArea" name="text_area" hidden></textarea>
					<input type="text" id="codeMan" name="code_man" hidden>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="collect">Collect</button>
					<button type="submit" class="btn btn-primary" id="save" name="save">Save changes</button>
				</div>
			</div>
		</div>
	</div>
</form>



<script>
	$(document).ready(function(){
		$("#collect").show();
		$("#save").hide();
		$('#theTable').DataTable();
		$('.jakamania').click(function(){
			var manid = $(this).data('manid');
			var flight = $(this).data('flight');
			$.get("views/partials/edit_manifest.php?mancode="+manid, function(data){
				$("#modalBody").html(data);
				$("#spinner").hide();
				$("#modalBody button").click(function(){
					var button = $(this);
					var tHapus = button.parent().parent();
					tHapus.remove();
				});
			});
			$('#target').val(flight);
			$('#codeMan').val(manid);
		});
		$("#collect").click(function(){
			var value = "",			
			tanggal = "<?php echo date('Y-m-d H:i:s') ?>",
			officer = "<?php echo $_SESSION['name'] ?>",
			code = "<?php echo date('ymdHms') ?>",
			flight = $('#target').val()
			reg = flight.split('-');
			$("#collect").hide();
			$("#save").show();
			$("#ember tr").each(function(){			
				var selrow = $(this),
				iSmu = selrow.find("td:eq(1) input").val(),
				iQty = selrow.find("td:eq(2) input").val(),
				iWeight = selrow.find("td:eq(3) input").val(),
				iContent = selrow.find("td:eq(4) input").val(),
				iKet = selrow.find("td:eq(5) input").val();

				value = value  + code + ','  + iSmu + ',' + iQty + ',' + iWeight + ','+ iContent + ',' + iKet + ',' + flight + ',' + reg[1] + ',' + tanggal + ',' + officer + ',';
			});
			$("#textArea").val(value);
			console.log(flight);
		});
	});
</script>