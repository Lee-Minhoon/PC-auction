<script src="./include/function.js"></script>
<?php
include "./include/function.php";
include "./include/header.php";
?>
<section class="register">
	<form method="post" action="register.php">
		<div style="margin: 40px;">
			<input type="radio" name="user_type" value="client" checked="checked" onclick="user_type_div(this.value)">회원
			<input type="radio" name="user_type" value="company" onclick="user_type_div(this.value)">업체<br>
			<script type="text/javascript">
				function user_type_div(type){
					if(type == "client"){
						document.getElementById("type_client").style.display = "block";
						document.getElementById("type_company").style.display = "none";
					}else{
						document.getElementById("type_company").style.display = "block";
						document.getElementById("type_client").style.display = "none";
					}
				}
			</script>
			<input type="text" name="id" placeholder="아이디"><br>
			<input type="password" name="password" placeholder="비밀번호"><br>
			<div id="type_client">
				<input type="text" name="nickname" placeholder="닉네임"><br>
			</div>
			<div id="type_company" style="display: none">
				<input type="text" name="company_name" placeholder="업체이름"><br>
				<input type="text" name="ceo_name" placeholder="대표이름"><br>
				<input type="text" name="company_tel" placeholder="업체전화번호"><br>
				<input type="text" name="corporate_regi_num" placeholder="사업자등록번호"><br>
				<input type="text" name="account" placeholder="사용할 계좌"><br>
				<input type="text" name="bank_kind" placeholder="은행 종류"><br>
			</div><br>
			<input type="submit" name="submit" value="완료">
			<button type="button" onclick="location.href = './index.php'">취소</button>
		</div>
	</form>
</section>
<?php
include "./include/footer.php";
?>

<?php
	if(isset($_POST['submit'])){
		register();
	}
	
	function register(){
		$post_user_type = $_POST['user_type'];
		$post_id = $_POST['id'];
		$post_pw = $_POST['password'];
		$register_array = array($post_user_type, $post_id, $post_pw);
		if($post_user_type == "client"){
			$post_nickname = $_POST['nickname'];
			array_push($register_array, $post_nickname);
		}else{
			$post_companyname = $_POST['company_name'];
			$post_ceo_name = $_POST['ceo_name'];
			$post_company_tel = $_POST['company_tel'];
			$post_corporate_regi_num = $_POST['corporate_regi_num'];
			$post_account = $_POST['account'];
			$post_bank_kind = $_POST['bank_kind'];
			array_push($register_array, $post_companyname, $post_ceo_name, $post_company_tel, $post_corporate_regi_num, $post_account, $post_bank_kind);
		}
		$conn = db_connect();

		//아이디 공백검사
		for($index = 0; $index < count($register_array); $index++){
			if($register_array[$index] == null){
				alert('공백이 있습니다.');
				exit();
			}
		}

		//아이디 중복검사
		$query = "SELECT * FROM user WHERE id = '$post_id'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if(isset($row)){
			alert_location('존재하는 ID입니다.', './register.php');
			exit();
		}

		//유저 DB삽입
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO user (user_kind, regi_date, id, password) VALUES ('$register_array[0]', '$now', '$register_array[1]', '$register_array[2]')";
		mysqli_query($conn, $query);

		$last_id = mysqli_insert_id($conn);
		//유저 타입에 따른 DB삽입
		if($post_user_type == "client"){
			$query = "INSERT INTO client (client_num, nickname, point, client_grade) VALUES ('$last_id', '$register_array[3]', 0, 'D')";
			mysqli_query($conn, $query);
		}else{
			$query = "INSERT INTO company (company_num, company_name, ceo_name, company_tel, corporate_regi_num, account_num, bank_kind, sell_total) VALUES ('$last_id', '$register_array[3]', '$register_array[4]', '$register_array[5]', '$register_array[6]', '$register_array[7]', '$register_array[8]', 0)";
			mysqli_query($conn, $query);
		}
		alert_location('가입이 완료되었습니다.', './index.php');
	}
?>