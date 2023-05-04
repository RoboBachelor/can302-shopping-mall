<?php
class DBController {
	private $host = "localhost";
	private $user = "can302";
	private $password = "can302";
	private $database = "can302_ass2";
	private $conn;
	
	function __construct() {
		$this->conn = $this->connectDB();
	}
	
	function connectDB() {
		$conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);
		return $conn;
	}
	
	function runQuery($query) {
        $result = mysqli_query($this->conn,$query);
		if (is_bool($result)) {
            return $result;
        }
        while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;
		}		
		if(!empty($resultset))
			return $resultset;
	}

    function getInsertedID() {
        return mysqli_insert_id($this->conn);
    }
	
	function numRows($query) {
		$result  = mysqli_query($this->conn,$query);
		$rowcount = mysqli_num_rows($result);
		return $rowcount;	
	}
}
?>