<?php 
if($_GET){
	require('../../config/config.php');
	require('../../models/database.php');
	require('../../models/m_manifest.php');
	$connection = new Database($host, $user, $pass, $database);
	$data = new Manifest($connection);
	$mancode = $_GET['mancode'];

	$find = $data->getall_first($mancode); ?>
	<style>
		#tResult thead tr th,
		#tResult tbody tr td{
			height: 0.7rem;
		}
		#tResult tbody tr td input{
			background-color: white;
		}
	</style>
	<table class="table table-sm table-hover table-striped text-nowrap" id="tResult" style="font-size: 0.7rem;">
		<thead>
			<tr style="height: 1.5rem;">
				<th></th>
				<th>SMU</th>
				<th>Quantity</th>
				<th>Weight</th>
				<th>Content</th>
				<th>Agent Name</th>
				<th>Type</th>
				<th>Type Name</th>
			</tr>
		</thead>
		<tbody id="ember">
			<?php 
			while($result = $find->fetch_object()):?>
				<tr>
					<td>
						<button type ="button"class="btn btn-sm btn-outline-primary">delete</button>
					</td>
					<td style="width: fit-content;"><input type="text" value="<?php echo $result->awb_number ?>" style="padding: 0; min-width: 4rem;"></td>
					<td><input type="number" value="<?php echo $result->koli ?>"></td>
					<td><input type="number" value="<?php echo $result->weight ?>"></td>
					<td><input type="text" value="<?php echo $result->comodity ?>"></td>
					<td><input type="text" value="<?php echo $result->remarks ?>"></td>
					<td><input type="text" value="<?php echo $result->type ?>"></td>
					<td><input type="text" value="<?php echo $result->type_name ?>"></td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
<?php } ?>

<script>
	
</script>
