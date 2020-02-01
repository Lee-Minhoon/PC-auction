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
	<section class="auctionDetail">
		<?php
		$conn = db_connect();
		$session_id = $_SESSION['session_id'];
		$query = "SELECT * FROM user WHERE id = '$session_id'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if($row['user_kind'] != 'company'){
			alert_back('업체 권한이 필요합니다.');
			exit();
		}
		$get_auction_num = $_GET['num'];

		$query = "SELECT * FROM auction WHERE auction_num = '$get_auction_num'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		$est_num = $row['est_num'];

		$now = date("Y-m-d H:i:s");
		$dead_time = $row['dead_time'];
		$timestamp = strtotime($dead_time."-1 minutes");
		$dead_time = date("Y-m-d H:i:s", $timestamp);
		if($now > $dead_time){
			alert_back('남은 경매시간이 1분미만이므로 입찰할 수 없습니다.');
			exit();
		}
		?>
		<div style="width: 990px; height:50px; border-bottom: 1px solid #DEE3EB;">
			<ul class="ul1" style="float: left; margin-left: 40px; padding: 0px;">
				<a onclick="javascript: history.back();" style="cursor: pointer;">입찰취소</a>
			</ul>
			<p class="title"><?= $row['auction_title'] ?>에 입찰하기</p>
		</div>
		<div style="width: 990px; border-bottom: 1px solid #DEE3EB;">
			<?php
			$query = "SELECT * FROM estimate_item WHERE est_num = '$est_num'";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				$parts_num = $row['parts_num'];
				$query = "SELECT * FROM parts WHERE parts_num = '$parts_num'";
				$parts = mysqli_query($conn, $query);
				$parts = mysqli_fetch_array($parts);
				echo '<p style="margin-left: 40px;">'.$parts['parts_kind'].': '.$parts['parts_name'].' '.$row['amount'].'개<br></p>';
			}
			?>
		</div>
		<form method="post">
			<div style="margin: 40px;">
				<input type="text" name="bid_content" style="width: 400px; height: 100px;"><br>
				<input type="submit" name="submit" value="입찰하기">
			</div>
		</form>
	</section>
</section>
<?php
include "./include/footer.php";
?>

<?php
	if(isset($_POST['submit'])){
		bidRegister();
	}
	
	function bidRegister(){
		$conn = db_connect();
		$get_auction_num = $_GET['num'];
		$session_number = $_SESSION['session_number'];
		$post_bid_content = $_POST['bid_content'];
		$register_array = array($post_bid_content);

		//입찰 공백검사
		for($index = 0; $index < count($register_array); $index++){
			if($register_array[$index] == null){
				alert('공백이 있습니다.');
				exit();
			}
		}

		$query = "SELECT * FROM auction WHERE auction_num ='$get_auction_num'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if($row['bid_state'] != '진행중'){
			alert('입찰상태를 확인해주세요.');
			exit();
		}

		$query = "SELECT * FROM auction WHERE auction_num = '$get_auction_num'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);

		$now = date("Y-m-d H:i:s");
		$dead_time = $row['dead_time'];
		$timestamp = strtotime($dead_time."-1 minutes");
		$dead_time = date("Y-m-d H:i:s", $timestamp);
		if($now > $dead_time){
			alert_location('남은 경매시간이 1분미만이므로 입찰할 수 없습니다.', 'auctionDetail.php?num='.$get_auction_num);
			exit();
		}

		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO bid (auction_num, company_num, bid_time, bid_content) VALUES ('$get_auction_num', '$session_number', '$now', '$post_bid_content')";
		mysqli_query($conn, $query);
		alert_location('입찰 하였습니다.', './auctionDetail.php?num='.$get_auction_num);
	}
?>