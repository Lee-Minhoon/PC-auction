<script src="./include/function.js"></script>
<?php
include "./include/function.php";
include "./include/header.php";
?>
<section class="index">
	<section class="left">
		<section class="left_1">
			<?php
			if(isset($_SESSION['session_id'])){
				include "profileLogin.php";
			}
			else{
				include "profileLogout.php";
			}
			?>
		</section>
		<?php include "./include/leftmenu.php"; ?>
	</section>
	<section class="main">
		<?php
		$conn = db_connect();
		$session_id = $_SESSION['session_id'];
		$session_num = $_SESSION['session_number'];
		$query = "SELECT * FROM user WHERE user_num = '$session_num'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		$mypage = '['.$row['user_kind'].'] '.$session_id.'님의 ';

		if($row['user_kind'] == 'client'){
		?>
			<div style="width: 990px; height:50px; border-bottom: 1px solid #DEE3EB;">
				<p class="title"><?= $mypage ?>마이페이지</p>
			</div>
			<script type="text/javascript">
				function target_frame(){
					document.form.target = "targetFrame";
					document.form.submit();
				}
				function target_window(){
					document.form.target = "_self";
				}
			</script>
			<form name="form" method="post" action="myest.php">
				<div style="margin: 40px;">
					<input type="radio" name="parts_kind" value="dst" checked="checked" onclick="select_mypage(this.value)">배송지등록
					<input type="radio" name="parts_kind" value="card" onclick="select_mypage(this.value)">카드등록
					<input type="radio" name="parts_kind" value="est" onclick="select_mypage(this.value)">견적함<br>
					<script type="text/javascript">
						function select_mypage(select){
							var string = select;
							var div = ["dst", "card", "est"];
							for(var index = 0; index < div.length; index++){
								if(string == div[index]){
									document.getElementById(div[index]).style.display = "block";
								}else{
									document.getElementById(div[index]).style.display = "none";
								}
							}
						}
					</script>
					<div style="width: 950px;">
						<div id="dst">
							<input class="inputBox" type="text" name="base_address" placeholder="기본주소"><br>
							<input class="inputBox" type="text" name="detail_address" placeholder="상세주소"><br>
							<input class="inputBox" type="text" name="zipcode" placeholder="우편번호"><br>
							<button name="registerDst" onclick="target_window()" formaction="mypage.php">추가</button>
						</div>
						<div id="card" style="display: none">
							<input class="inputBox" type="text" name="card_num" placeholder="카드번호"><br>
							<input class="inputBox" type="text" name="card_kind" placeholder="카드종류"><br>
							<input class="inputBox" type="text" name="expiration" placeholder="유효기간"><br>
							<button name="registerCard" onclick="target_window()" formaction="mypage.php">추가</button>
						</div>
						<div id="est" style="display: none">
							<select name="estimate" style="width: 250px; height: 25px;" onChange="target_frame()">
								<?php
								$conn = db_connect();
								$session_number = $_SESSION['session_number'];

								$query = "SELECT * FROM estimate WHERE client_num = '$session_number'";
								$result = mysqli_query($conn, $query);
								while($row = mysqli_fetch_array($result)){
									$est_num = $row['est_num'];
									echo '<option value="'.$est_num.'">'.$row['gen_date'].' / '.$row['est_num'].'번 / '.$row['est_total'].'원</option>';
								}
								?>
							</select>
							<iframe name="targetFrame" style="width: 905px; height: 300px;"></iframe><br>
						</div>
					</div>
				</div>
			</form>
		<?php
		}
		?>
	</section>
</section>
<?php
include "./include/footer.php";
?>

<?php
	if(isset($_POST['registerDst'])){
		registerDst();
	}
	if(isset($_POST['registerCard'])){
		registerCard();
	}

	function registerDst(){
		$conn = db_connect();
		$session_num = $_SESSION['session_number'];
		$post_base_address = $_POST['base_address'];
		$post_detail_address = $_POST['detail_address'];
		$post_zipcode = $_POST['zipcode'];
		$register_array = array($post_base_address, $post_detail_address, $post_zipcode);

		//주소 공백검사
		for($index = 0; $index < count($register_array); $index++){
			if($register_array[$index] == null){
				alert('공백이 있습니다.');
				exit();
			}
		}

		//주소 중복검사
		$query = "SELECT * FROM destination WHERE basic_address = '$register_array[0]' AND detail_address = '$register_array[1]'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if(isset($row)){
			$query = "SELECT * FROM destination_of_client WHERE basic_address = '$register_array[0]' AND detail_address = '$register_array[1]'";
			$result = mysqli_query($conn, $query);
			$row = mysqli_fetch_array($result);
			if(isset($row)){
				alert('중복된 배송지입니다.');
				exit();
			}
			$query = "INSERT INTO destination_of_client (basic_address, detail_address, client_num) VALUES ('$register_array[0]', '$register_array[1]', '$session_num')";
			mysqli_query($conn, $query);
			alert('등록이 완료되었습니다.');
		}else{
			$query = "INSERT INTO destination (basic_address, detail_address, zipcode) VALUES ('$register_array[0]', '$register_array[1]', '$register_array[2]')";
			mysqli_query($conn, $query);
			$query = "INSERT INTO destination_of_client (basic_address, detail_address, client_num) VALUES ('$register_array[0]', '$register_array[1]', '$session_num')";
			mysqli_query($conn, $query);
			alert('등록이 완료되었습니다.');
		}
	}

	function registerCard(){
		$conn = db_connect();
		$session_num = $_SESSION['session_number'];
		$post_card_num = $_POST['card_num'];
		$post_card_kind = $_POST['card_kind'];
		$post_expiration = $_POST['expiration'];
		$register_array = array($post_card_num, $post_card_kind, $post_expiration);

		//카드 공백검사
		for($index = 0; $index < count($register_array); $index++){
			if($register_array[$index] == null){
				alert('공백이 있습니다.');
				exit();
			}
		}

		//카드 중복검사
		$query = "SELECT * FROM card WHERE card_num = '$post_card_num'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if(isset($row)){
			alert_location('존재하는 카드입니다.', './register.php');
			exit();
		}

		$query = "INSERT INTO card (card_num, client_num, card_kind, expiration) VALUES ('$register_array[0]', '$session_num', '$register_array[1]', '$register_array[2]')";
		mysqli_query($conn, $query);
		alert('등록이 완료되었습니다.');
	}
?>