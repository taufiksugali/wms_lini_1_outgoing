<?php 
if($_GET){
	require('../../config/config.php');
	require('../../models/database.php');
	require('../../models/m_manifest.php');
	$connection = new Database($host, $user, $pass, $database);
	$data = new Manifest($connection);
	$smu = $_GET['smu'];

	$find = $data->searchlike($smu); ?>
	<table class=" text-nowrap" id="tResult">
		<tbody>
			<?php
			$thasil = 0;
			while($result = $find->fetch_object()):?>
				<?php 
				$thasil++;
				if($thasil < 2){
					if(intval($result->jkoli) === 0 && intval($result->jweight) === 0){
						$varkoli = $result->quantity;
						$varweight = $result->weight;
						$status="ready";
						$button="";
						$color="azure";
					}
					elseif(intval($result->jkoli) > 0 && intval($result->jweight) > 0){
						if(intval($result->jkoli) < intval($result->quantity) && intval($result->jweight) < intval($result->weight)){						
							$varkoli = intval($result->quantity)-intval($result->jkoli);
							$varweight = intval($result->weight)-intval($result->jweight);
							$status="partial ".$result->jkoli."/".$result->quantity;
							$button="";
							$color="salmon";
						}
						elseif(intval($result->jkoli) === intval($result->quantity) && intval($result->jweight) === intval($result->weight)){						
							$varkoli = intval($result->quantity);
							$varweight = intval($result->weight);
							$status="Full Manifested";
							$button="disabled";
							$color="greenyellow";
						}
					}

				?>
				<tr>
					<td style="">
						<button type="button" class="btn btn-sm btn-primary <?php echo $button ?>" > Sellect </button>
					</td>
					<td>
						<div
						style="font-size: 0.7rem; width: 7em; font-weight: bold;"
						><?php echo $result->smu ?></div>
					</td>
					<td>
						<div
						style="font-size: 0.9rem; width: 5em; height: 1.5rem; font-weight: bold; overflow: hidden; padding-top: 5px; line-height: 1rem;"
						><?php echo $varkoli ?></div>

					</td>
					<td>
						<div
						style="font-size: 0.9rem; width: 5em; height: 1.5rem; font-weight: bold; overflow: hidden; padding-top: 5px; line-height: 1rem;"
						><?php echo $varweight ?></div>

					</td>
					<td style="width: fit-content;">
						<div
						style="font-size: 0.7rem; width: fit-content; height: 1.5rem; font-weight: bold; overflow: hidden; padding-top: 5px; line-height: 1rem;"
						><?php echo strtoupper(preg_replace('/[^A-Za-z0-9\  ]/', '', $result->comodity)) ?></div>

					</td>
					<td>
						<div
						style="font-size: 0.7rem; width: 7em; height: 1.5rem; font-weight: bold; overflow: hidden; padding-top: 5px; line-height: 1rem;"
						><?php echo strtoupper(preg_replace('/[^A-Za-z0-9\  ]/', '',  $result->agent_name)) ?></div>
					</td>
					<td>
						<div class="text-center" 
						style="font-size: 0.7rem; width: 7em; height: 1.5rem; font-weight: bold; overflow: hidden; padding-top: 5px; line-height: 1rem; border-radius: 5px; background-color: <?php echo $color; ?>;"
						><?php echo $status ?></div>
					</td>
				</tr>
			<?php }; ?>
			<?php endwhile; ?>
		</tbody>
	</table>
<?php } ?>

<script>
	
</script>
