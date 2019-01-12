<?php
session_start();
Class dbObj{
  // For testing Purposes
  var $server = "127.0.0.1";
  var $username = "root";
  var $password = "nilemonitor354";
  var $db = "iAdvisor";
  var $port = "3306";

  // var $server = "104.196.186.97";
  // var $username = "rpadgett";
  // var $password = "iadvisor";
  // var $db = "iAdvisor";
  // var $port = "3306";
  function getConnstring() {
		$con = mysqli_connect($this->server, $this->username, $this->password, $this->db) or die("Connection failed: " . mysqli_connect_error());

		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		} else {
			$this->conn = $con;
		}
		return $this->conn;
	}
}

?>
