<?php 
class Login{
	private $mysqli;
	function __construct($conn){
		$this-> mysqli = $conn;
	}
	public function panggil($username,$password) {
		$db = $this->mysqli->conn;
		$sql ="SELECT COUNT(username) AS jumlah FROM akun WHERE username='$username' AND password='$password'";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
	public function panggilall($username) {
		$db = $this->mysqli->conn;
		$sql ="SELECT * FROM akun WHERE username='$username'";
		$query = $db->query($sql) or die ($db->error);

		return($query);
	}
}