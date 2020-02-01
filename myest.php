<script src="./include/function.js"></script>
<?php
	include "./include/function.php";
	$conn = db_connect();
	$post_est_num = $_POST['estimate']; //용도
	
	$query = "SELECT * FROM estimate_item WHERE est_num = '$post_est_num'";
	$result = mysqli_query($conn, $query);
	while($row = mysqli_fetch_array($result)){
		$parts_num = $row['parts_num'];
		$query = "SELECT * FROM parts WHERE parts_num = '$parts_num'";
		$parts = mysqli_query($conn, $query);
		$parts = mysqli_fetch_array($parts);
		echo $parts['parts_kind'].': '.$parts['parts_name'].' '.$row['amount'].'개<br>';
	}
?>