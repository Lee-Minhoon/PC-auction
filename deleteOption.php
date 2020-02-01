<script src="./include/function.js"></script>
<?php
include "./include/function.php";
$post_hidden = $_POST['hidden'];
$start_point = $_POST['start_point'];
$list = $_POST['list'];
$conn = db_connect();

$query = "SELECT * FROM purpose_and_program ORDER BY option_num DESC LIMIT $start_point, $list";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_array($result)){
	$List[] = $row['option_num'];
}

for($index = 0; $index < count($post_hidden); $index++){
	if($post_hidden[$index]){
		$query = "DELETE FROM purpose_and_program WHERE option_num = '$List[$index]'";
		mysqli_query($conn, $query);
	}
}
go_location('./optionList.php');
?>