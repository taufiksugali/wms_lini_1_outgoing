<?php
if(isset($_POST['submit'])){
	$smu = $_POST['smu'];
	$subs = substr($smu, 4);
	var_dump($subs);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<form action="" method="post">
		<label for="">SMU</label>
		<input type="text" name="smu">
		<input type="submit" name="submit">
	</form>

</body>
</html>