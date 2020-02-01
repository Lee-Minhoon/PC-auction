<script src="./include/function.js"></script>
<?php
include "./include/function.php";
$post_parts = $_POST['parts'];
$post_hidden = $_POST['hidden'];
$start_point = $_POST['start_point'];
$list = $_POST['list'];
$conn = db_connect();

$query = "SELECT * FROM parts ORDER BY parts_num DESC LIMIT $start_point, $list";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_array($result)){
	$List[] = $row['parts_num'];
}

for($index = 0; $index < count($post_hidden); $index++){
	if($post_hidden[$index]){
		$query = "DELETE FROM $post_parts[$index] WHERE parts_num = '$List[$index]'";
		mysqli_query($conn, $query);
		$query = "DELETE FROM parts WHERE parts_num = '$List[$index]'";
		mysqli_query($conn, $query);
	}
}
go_location('./partsList.php');
?>