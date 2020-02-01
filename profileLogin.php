<div style="margin: 10px;">
	<?php
	$conn = db_connect();
	$session_id = $_SESSION['session_id'];
	$session_num = $_SESSION['session_number'];

	$query = "SELECT * FROM user WHERE id = '$session_id'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_array($result);
	$userkind = $row['user_kind'];

	echo '사용자: '.$userkind.'<br>';
	echo '<a href="mypage.php">'.$session_id.'</a>님 환영합니다.<br>';
	if($userkind == 'client'){
		$query = "SELECT * FROM client WHERE client_num = '$session_num'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		echo $row['client_grade'].'등급<br>';
	}
	?>
	<button type="button" onclick="location.href = './logout.php'">로그아웃</button>
</div>