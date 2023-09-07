<?php 
class User{
	private $mysqli;
	function __construct($conn){
		$this-> mysqli = $conn;
	}
	public function panggil($username) {
		$db = $this->mysqli->conn;
		$sql ="SELECT * FROM akun WHERE username='$username'";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
}