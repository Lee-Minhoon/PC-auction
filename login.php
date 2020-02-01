<script src="./include/function.js"></script>
<?php
include "./include/function.php";
include "./include/header.php";
?>
<section class="login">
	<form method="post" action="login.php">
		<div style="margin: 40px;">
			<input type="text" name="id" placeholder="아이디"><br>
			<input type="password" name="pw" placeholder="비밀번호"><br>
			<input type="submit" name="submit" value="로그인">
			<button type="button" onclick="location.href = './index.php'">취소</button>
		</div>
	</form>
</section>
<?php
include "./include/footer.php";
?>

<?php
	if(isset($_POST['submit'])){
		login();
	}
	
	function login(){
		$post_id = $_POST['id'];
		$post_pw = $_POST['pw'];
		$conn = db_connect();

		//로그인 성공
		$query = "SELECT * FROM user WHERE id = '$post_id'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if($post_id == $row['id'] && $post_pw == $row['password']){
			$_SESSION['session_id'] = $row['id'];
			$_SESSION['session_number'] = $row['user_num'];
			go_location('./index.php');

		//비밀번호 틀림
		}else if($post_id == $row['id'] && $post_pw != $row['pw']){
			alert_location('비밀번호가 틀렸습니다.', './login.php');

		//아이디 틀림
		}else{
			alert_location('아이디가 틀렸습니다.', './login.php');
		}
	}
?>