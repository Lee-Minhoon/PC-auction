<?php
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);
	session_start();
	
	function db_connect(){
		$conn = mysqli_connect("localhost", "root", "1234", "pcdb");
		return $conn;
	}

	function alert($alert){
		echo "<script>if(!alert('$alert'));</script>";
	}
	
	function go_location($href){	
		echo "<script>location.href = '$href';</script>";
	}

	function alert_back($alert){
		echo "<script>if(!alert('$alert')) history.back();</script>";
	}

	function alert_location($alert, $href){
		echo "<script>if(!alert('$alert')) location.href = '$href';</script>";
	}
?>