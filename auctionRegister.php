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
			$session_id = $_SESSION['session_id'];
			$query = "SELECT * FROM user WHERE id = '$session_id'";
			$result = mysqli_query($conn, $query);
			$row = mysqli_fetch_array($result);
			if($row['user_kind'] != 'client'){
				alert_back('고객 권한이 필요합니다.');
				exit();
			}
			?>
		</section>
		<?php include "./include/leftmenu.php"; ?>
	</section>
	<section class="auctionRegister">
		<div style="width: 990px; height:50px; border-bottom: 1px solid #DEE3EB;">
			<ul class="ul1" style="float: left; margin-left: 40px; padding: 0px;">
				<a href="auctionList.php">역경매목록</a>
				<ul class="ul2">
					<li><a href="partsCPU.php">진행중</a></li>
					<li><a href="partsMainboard.php">마감</a></li>
					<li><a href="partsMainMemory.php">거래종료</a></li>
				</ul>
			</ul>
			<p class="title">역경매등록</p>
		</div>
		<form method="post" action="auctionRegister.php">
			<div style="margin: 40px;">
				<select name="estimate" style="width: 250px; height: 25px;">
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
				</select><br>
				<input type="text" name="title" placeholder="역경매 제목" style="width: 250px; height: 25px;"><br>
				<input type="text" name="time" placeholder="진행시간(분)" style="width: 250px; height: 25px;"><br>
				<input type="submit" name="submit" value="역경매 등록">
				<button type="button" onclick="location.href = './auctionList.php'">취소</button>
			</div>
		</form>
	</section>
</section>
<?php
include "./include/footer.php";
?>

<?php
	if(isset($_POST['submit'])){
		auctionRegister();
	}
	
	function auctionRegister(){
		$conn = db_connect();
		$session_id = $_SESSION['session_id'];
		$query = "SELECT * FROM user WHERE id = '$session_id'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if($row['user_kind'] != 'client'){
			alert_back('고객 권한이 필요합니다.');
		}
		$session_number = $_SESSION['session_number'];
		$post_estimate = $_POST['estimate'];
		$post_title = $_POST['title'];
		$post_time = $_POST['time'];
		$register_array = array($post_estimate, $post_title, $post_time);

		//용도 공백검사
		for($index = 0; $index < count($register_array); $index++){
			if($register_array[$index] == null){
				alert('공백이 있습니다.');
				exit();
			}
		}

		$regi_time = date("Y-m-d H:i:s");
		$timestamp = strtotime($post_time." minutes");
		$dead_time = date("Y-m-d H:i:s", $timestamp);

		$query = "INSERT INTO auction (client_num, est_num, regi_time, dead_time, auction_title, bid_state) VALUES ('$session_number', '$post_estimate', '$regi_time', '$dead_time', '$post_title', '진행중')";
		mysqli_query($conn, $query);

		$last_id = mysqli_insert_id($conn);
		alert_location('등록 하였습니다.', './auctionDetail.php?num='.$last_id);
	}
?>