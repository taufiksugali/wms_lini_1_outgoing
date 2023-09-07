<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
		<!-- <input type="checkbox" value="tes" name="chkbox" id="cek"> -->
		<input type="checkbox" id="checkAll" name="chkbox[]" value="nama">
		<label for="">nama : </label>
		<!-- <input type="text" name="nama"> -->
		<input type="button" value="proses" name="proses" onclick="check()">
		<input type="button" value="cakvalue" name="noname" onclick="checkit()">

	<script src="assets/jquery/jquery-3.6.0.js"></script>
	<script>
		function check(){
			var input = $('#checkAll');
			var x = input.length;
			input.prop('checked', true);
			console.log(x);
		};
		function checkit(){
			var inputan = $('#checkAll');
			 // var a = $("#checkAll").val();
			 // console.log(a);
			 var y = input.checked();
			 console.log(y);
		}
	</script>
</body>
</html>