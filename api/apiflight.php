<?php 
if(isset($_GET['data'])){

	require_once('../config/config.php');
	require_once('../models/database.php');
	include('../models/m_btb.php');
	$connection = new Database($host, $user, $pass, $database);
	$flight = new Btb($connection);
	$value = $_GET['data'];
	$destination = $flight->cariflight($value);
	$nilai=0;
	while ($result = $destination->fetch_object()) {
		$nilai++;
		if($nilai === 1){
			echo "<";
			echo htmlspecialchars("option class='apisel' selected value=");
			echo $result->flight_no;
			echo htmlspecialchars(" class='nrow'");
			echo ">";
			echo $result->flight_no;
			echo "<";
			echo htmlspecialchars("/option");
			echo ">";
		}else{
			echo "<";
			echo htmlspecialchars("option class='apisel' value=");
			echo $result->flight_no;
			echo htmlspecialchars("");
			echo ">";
			echo $result->flight_no;
			echo "<";
			echo htmlspecialchars("/option");
			echo ">";
		// echo "<br>";
		}
	}
}

?>