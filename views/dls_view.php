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
								<div class="mb-2 position-relative">
									<select name="" id="type" style="height: 2em; width: 100%;">
										<option value="PAG">PAG</option>
										<option value="PKC">PKC</option>
										<option value="PAG FREELOAD">PAG FREELOAD</option>
										<option value="PKC FREELOAD">PKC FREELOAD</option>
										<option value="BULK">BULK</option>
									</select>
									<label>Select type</label>
								</div>
								<div class="mb-2 position-relative">
									<input class="form-control form-control-sm" type="text" id="typeName">
									<label>Type name</label>
								</div>
								<div class="position-relative">
									<input class="form-control form-control-sm" type="text" name="smu" id="inputSmu">
									<label>SMU</label>
									<div class="spinner-border spinner-border-sm" role="status" id="spinner" style="top: 5px; right:5px;position: absolute;">
										<span class="visually-hidden">loading..</span>
									</div>
								</div>
							</div>
							<div class="col-md-9 px-2 border rounded bg-light ms-3" style="max-height: 15em; min-height: fit-content; overflow: auto;" id="resultArea">
								
							</div>
						</div>													
					</div>
				</div>
			</div>
		</div>
		<div class="mt-3 bg-light p-3" id="manArea" style="overflow: auto;">
			<div class="d-flex justify-content-center">
				<div class="border border-dark rounded px-5 py-1" id="totalSmu">
					Total selected smu: <i id="nTSmu" style="color: maroon;"></i> smu 	
				</div>
			</div>

			<form action="views/print_manifest2.php" target="_blank" method="post">
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
			<div class="row g-0 p-0">
				<div class="col-md-8 border shadow" style="overflow-x: auto;">
					<table class="text-nowrap mt-2" id="tCreateManifest" style="font-size: 0.8rem">
						<thead>
							<tr>
								<th></th>
								<th>SMU</th>
								<th>Quantity</th>
								<th>Weight</th>
								<th>Content</th>
								<th>Keterangan</th>
								<th>Type</th>
								<th>Name</th>
							</tr>
						</thead>
						<tbody id="ember">

						</tbody>
					</table>
				</div>				
				<div class="col-md-4 px-3">
					<table class="table table-dark text-nowrap mt-2" style="font-size: 0.7rem">
						<thead>
							<tr>
								<th>Type Name</th>
								<th>Quantity</th>
								<th>weight</th>
							</tr>
						</thead>
						<tbody id="typeEmber">
						</tbody>
					</table>
				</div>		
			</div>
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
					coli = aktif.parent().parent().find("td:eq(2) div").text().replace(/[^\w\s]/gi, ''),
					berat = aktif.parent().parent().find("td:eq(3) div").text().replace(/[^\w\s]/gi, ''),
					content = aktif.parent().parent().find("td:eq(4) div").text().replace(/[^\w\s]/gi, ''),
					agent = aktif.parent().parent().find("td:eq(5) div").text().replace(/[^\w\s]/gi, ''),
					type = $("#type").val().replace(/[^\w\s]/gi, ''),
					typename = $("#typeName").val().replace(/[^\w\s]/gi, ''),
					gabung = type+typename,
					clasan = gabung.replace( /\s/g, '');
					$("#ember").prepend("<tr class='bbaris'>"
						+"<td><button class='btn btn-sm btn-danger click-me'><i class='fa-solid fa-circle-minus'></i></button></td>"
						+"<td class='px-2'>"+smu+"</td>"
						+"<td><input type='number' value='"+coli+"' style='width: 5em'></td>"
						+"<td><input type='number' value='"+berat+"' style='width: 5em'></td>"
						+"<td><input type='text' value='"+content+"' ></td>"
						+"<td><input type='text' value='"+agent+"' ></td>"
						+"<td class='px-3'>"+type+"</td>"
						+"<td class='px-2'>"+typename+"</td>"
						+"</tr>"

						);
					var nameType = type+' '+typename;
					

					if( $('#'+clasan).length ){
						var typeKoli = parseInt( $('#'+clasan).find("td:eq(1)").text() ) + parseInt(coli),
						typeBerat = parseInt( $('#'+clasan).find("td:eq(2)").text() ) + parseInt(berat);
						$('#'+clasan).find("td:eq(1)").text(typeKoli);
						$('#'+clasan).find("td:eq(2)").text(typeBerat);
					}else{
						$("#typeEmber").append('<tr id="'+clasan+'">'
							+'<td>'+nameType+'</td>'
							+'<td>'+coli+'</td>'
							+'<td>'+berat+'</td>'
							+'</tr>');
					}




					aktif.text("cancel");
					aktif.addClass("disabled");
					// aktif.parent().parent().remove();
					$(".click-me").unbind().click(function(){
						var kurangi2 = 0;
						$("#ember .bbaris").each(function(){
							kurangi2+=1;
						});				
						var ini = $(this),
							tHapus = ini.parent().parent(),
							bsmu = tHapus.find("td:eq(1)").text(),
							tId = tHapus.find("td:eq(6)").text() + tHapus.find("td:eq(7)").text(),
							getId = tId.replace( /\s/g, ''),
							// sasaran = $("td:contains('"+bsmu+"')"),
							sasaran = $("#resultArea #tResult tbody td:contains('"+bsmu+"')"),
							dbuton = sasaran.parent().find("button"),
							dlsQty = parseInt($('#'+getId).find("td:eq(1)").text()),
							dlsKilo = parseInt($('#'+getId).find("td:eq(2)").text()),
							currentQty = parseInt(tHapus.find("td:eq(2) input").val()),
							currentKilo = parseInt(tHapus.find("td:eq(3) input").val());

						if( (dlsQty-currentQty)<1 ){
							$('#'+getId).remove();
						}else{
							$('#'+getId).find("td:eq(1)").text(dlsQty-currentQty);
							$('#'+getId).find("td:eq(2)").text(dlsKilo-currentKilo);
						}

						// console.log($("#ember tr").length);
						dbuton.removeClass("disabled");
						dbuton.text("sellect");
						tHapus.remove();
						tSmuSelect=kurangi2;
						$("#nTSmu").text(kurangi2-1);
						console.log(sasaran);
						console.log(bsmu)
					});

					$("#ember").change(function(){
						console.log("tes")
					});

					$("#ember input").change(function(e){
						var parnt = $(this).parent().parent(),
							tId = parnt.find("td:eq(6)").text() + parnt.find("td:eq(7)").text(),
							getId = tId.replace( /\s/g, ''),
							getType = parnt.find("td:eq(6)").text(),
							getTypeName = parnt.find("td:eq(7)").text();
							// dlsQty = parseInt($('#'+getId).find("td:eq(1)").text()),
							// dlsKilo = parseInt($('#'+getId).find("td:eq(2)").text()),
							// currentQty = parseInt(parnt.find("td:eq(2) input").val()),
							// currentKilo = parseInt(parnt.find("td:eq(3) input").val()),
						var	jumlahQty = 0,
							jumlahKilo = 0;
						$("#ember tr").each(function() {
							var tEmberType = $(this).find("td:eq(6)").text(),
								tEmberName = $(this).find("td:eq(7)").text();
							if( tEmberType === getType && tEmberName === getTypeName){								
								jumlahQty += parseInt($(this).find("td:eq(2) input").val());
								jumlahKilo += parseInt($(this).find("td:eq(3) input").val());								
							};
						});
						$('#'+getId).find("td:eq(1)").text(jumlahQty);
						$('#'+getId).find("td:eq(2)").text(jumlahKilo);
						$("#create").hide();
						$("#collect").show();
					})
				}); //here
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
				iSmu = selrow.find("td:eq(1)").text(),
				iQty = selrow.find("td:eq(2) input").val(),
				iWeight = selrow.find("td:eq(3) input").val(),
				iContent = selrow.find("td:eq(4) input").val(),
				iKet = selrow.find("td:eq(5) input").val();
				iType = selrow.find("td:eq(6)").text();
				iTypeName = selrow.find("td:eq(7)").text();

				value = value  + code + ','  + iSmu + ',' + iQty + ',' + iWeight + ','+ iContent + ',' + iKet + ',' + flight + ',' + reg[1] + ',' + tanggal + ',' + officer + ','+iType+','+iTypeName+',';
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