<?php
	class Database{
		private $dsn = "mysql:host=localhost;dbname=bt_jengopay";
		private $user = "root";
		private $password = "";
		public $conn;

		public function __construct() {
			try{
				$this->conn = new PDO($this->dsn,$this->user,$this->password);
				//echo 'Connection Successful';
			}catch(PDOException $e){
				echo 'Connection Failed' .$e->getMessage();
			}
		}

		public function read(){
			$data = array();
			$sql = "SELECT * FROM buildings";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}
	$obj = new Database();
?>