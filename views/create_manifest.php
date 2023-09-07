<?php 
require_once('models/m_manifest.php');
$manifest = new Manifest($connection);
$callflight = $manifest->callflight();
date_default_timezone_set('Asia/Jakarta');
?>
<div class = "kontener2 px-5 py-4">
	<nav style="--bs-breadcrumb-divider: '》';" aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item ps-4" aria-current="page"><i class="fa-solid fa-file-lines"></i> Manifest
			</li>
			<li class="breadcrumb-item ps-4 active" aria-current="page">Create Manifest</li>
		</ol>
	</nav>
	<div class = "content p-4" style="font-family: roboto;">
		<h6><i class="fas fa-newspaper"></i> Create manifest</h6>
		<div class="row g-0 p-0 m-0">
			<div class = "description col-sm-2 px-3">
				<h5>Create manifest</h5>
				<p>create manifest data based on flight data </p>
			</div>
			<div class = "main-form col-sm-10 px-3 ">
				<div class="mb-3">
					<div class="" 	 id="manifest">
						<div class="row g-1 p-0 m-0">
							<div class="position-relative col-md-2 mt-2 me-2">
								<input class="form-control form-control-sm" type="text" name="smu" id="inputSmu">
								<label>SMU</label>
								<div class="spinner-border spinner-border-sm" role="status" id="spinner" style="top: 5px; right:5px;position: absolute;">
									<span class="visually-hidden">loading..</span>

								</div>
							</div>
							<div class="col-md-9 px-2 border rounded bg-light" style="max-height: 15em; min-height: fit-content; overflow: auto;" id="resultArea">
								
							</div>
						</div>													
					</div>
				</div>
			</div>
		</div>
		<div class="mt-5 bg-light p-3" id="manArea" style="overflow: auto;">
			<div class="d-flex justify-content-center">
				<div class="border border-dark rounded px-5 py-1" id="totalSmu">
					Total selected smu: <i id="nTSmu" style="color: maroon;"></i> smu 	
				</div>
			</div>

			<form action="views/print_manifest.php" target="_blank" method="post">
				<button type="button" class="btn btn-outline-primary" id="collect">collect</button>
				<button type="submit" class="btn btn-outline-info" id="create" name="create">create</button>
				<textarea id="textArea" name="textArea" hidden></textarea>
				<select id="select">
					<?php
					while ($result = $callflight->fetch_object()) :?>
						
						<option value="<?php echo $result->flight_no ?>"><?php echo $result->flight_no ?></option>

					<?php endwhile; ?>
				</select>		
			</form>
			<table id="tCreateManifest">
				<thead>
					<tr>
						<th></th>
						<th>SMU</th>
						<th>Quantity</th>
						<th>Weight</th>
						<th>Content</th>
						<th>Keterangan</th>
					</tr>
				</thead>
				<tbody id="ember">
					
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		var tSmuSelect=0;
		$("#spinner").hide();
		$("#manArea").hide();
		$("#create").hide();
		$("#totalSmu").hide();
		$("#inputSmu").change(function(){
			$("#collect").show();
			$("#create").hide();
			$("#spinner").show();
			var target = $("#inputSmu").val();
			// $("#resultArea").load("views/partials/search.php?smu="+target);
			$.get("views/partials/search.php?smu="+target, function(data){
				$("#resultArea").html(data);
				$("#spinner").hide();
				$("#tResult tbody tr").each(function(){
					var baris = $(this),
					bawb = baris.find("td:eq(1)").text(),
					matches = 0;
					$(":input").each(function(i,val){
						if($(this).val() == bawb){
							matches++;
						}
					})
					if(matches > 0 ){
						var x = baris.find("button");
						x.addClass("disabled");
						x.text("cancel");
					}
				});
				$("#tResult button").click(function(){
					tSmuSelect = tSmuSelect + 1;
					$("#nTSmu").text(tSmuSelect);
					$("#manArea").show();
					$("#create").hide();
					$("#collect").show();
					$("#totalSmu").show();
					var aktif = $(this),
					smu = aktif.parent().parent().find("td:eq(1) div").text(),
					coli = aktif.parent().parent().find("td:eq(2) div").text(),
					berat = aktif.parent().parent().find("td:eq(3) div").text(),
					content = aktif.parent().parent().find("td:eq(4) div").text(),
					agent = aktif.parent().parent().find("td:eq(5) div").text();
					$("#ember").append("<tr>"
						+"<td><button class='btn btn-sm btn-danger'><i class='fa-solid fa-circle-minus'></i></button></td>"
						+"<td><input type='text' value='"+smu+"' ></td>"
						+"<td><input type='number' value='"+coli+"' ></td>"
						+"<td><input type='number' value='"+berat+"' ></td>"
						+"<td><input type='text' value='"+content+"' ></td>"
						+"<td><input type='text' value='"+agent+"' ></td>"
						+"</tr>"

						);
					aktif.text("cancel");
					aktif.addClass("disabled");
					// aktif.parent().parent().remove();
					$("#ember tr td .btn").click(function(){
						var kurangi2 = 0;
						$("#ember tr").each(function(){
							kurangi2++;
						});				
						var ini = $(this);
						var tHapus = ini.parent().parent();
						var bsmu = tHapus.find("td:eq(1) input").val();
						var sasaran = $("td:contains('"+bsmu+"')");
						var dbuton = sasaran.parent().find("td:eq(0) button");
						dbuton.removeClass("disabled");
						dbuton.text("sellect");
						tHapus.remove();
						tSmuSelect=kurangi2;
						$("#nTSmu").text(kurangi2);
					});
					$("#ember input").change(function(){
						$("#create").hide();
						$("#collect").show();
					})
				});
			});
		});
		$("#collect").click(function(){
			var value = "",			
			tanggal = "<?php echo date('Y-m-d H:i:s') ?>",
			officer = "<?php echo $_SESSION['name'] ?>",
			code = "<?php echo date('ymdHms') ?>",
			flight = $("#select").val(),
			reg = flight.split('-');
			$("#collect").hide();
			$("#create").show();
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
		})
		$("#select").change(function(){
			$("#collect").show();
			$("#create").hide();
		});
		$("#create").click(function(){
			setTimeout(function(){
				location.reload();
			},3000);
		});

	});
	
</script>