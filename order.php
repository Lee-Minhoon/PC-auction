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
		$get_auction_num = $_GET['num'];
		$post_company_num = $_POST['company_num'];

		$query = "SELECT * FROM user WHERE user_num = '$post_company_num'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);

		$query ="SELECT * FROM auction WHERE auction_num = '$get_auction_num'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);

		if($row['bid_state'] != '진행중'){
			alert_back('진행중인 경매가 아닙니다.');
			exit();
		}
		?>
		<div style="width: 990px; height:50px; border-bottom: 1px solid #DEE3EB;">
			<ul class="ul1" style="float: left; margin-left: 40px; padding: 0px;">
				<a onclick="javascript: history.back();" style="cursor: pointer;">낙찰취소</a>
			</ul>
			<p class="title"><?= $row['id'] ?>낙찰</p>
		</div>
		<form method="post" action="order.php?num=<?= $get_auction_num ?>">
			<div style="margin: 40px;">
				<input type="hidden" name="company_num" value="<?= $post_company_num ?>">
				<input type="text" name="order_total" placeholder="주문총액" style="width: 300px; height: 25px;"><br>
				<select name="card_num" style="width: 300px; height: 25px;">
					<option value="">카드</option>
					<?php
					$conn = db_connect();
					$session_number = $_SESSION['session_number'];

					$query = "SELECT * FROM card WHERE client_num = '$session_number'";
					$result = mysqli_query($conn, $query);
					while($row = mysqli_fetch_array($result)){
						$card_num = $row['card_num'];
						echo '<option value="'.$card_num.'">'.$row['card_num'].' / '.$row['card_kind'].'</option>';
					}
					?>
				</select><br>
				<select name="dst_address" style="width: 300px; height: 25px;">
					<option value="">배송지</option>
					<?php
					$conn = db_connect();
					$session_number = $_SESSION['session_number'];

					$query = "SELECT * FROM destination_of_client WHERE client_num = '$session_number'";
					$result = mysqli_query($conn, $query);
					while($row = mysqli_fetch_array($result)){
						$base_address = $row['basic_address'];
						$detail_address = $row['detail_address'];
						echo '<option value="'.$base_address.$detail_address.'">'.$base_address.' / '.$detail_address.'</option>';
					}
					?>
				</select><br>
				<input type="text" name="use_point" placeholder="사용포인트 (제외한 금액이 결제됩니다)" style="width: 300px; height: 25px;"><br>
				<input type="submit" name="submit" value="주문하기">
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
		$get_auction_num = $_GET['num'];
		$post_company_num = $_POST['company_num'];
		$post_order_total = $_POST['order_total'];
		$post_card_num = $_POST['card_num'];
		$post_dst_address = $_POST['dst_address'];
		$post_use_point = $_POST['use_point'];
		$register_array = array($post_company_num, $post_order_total, $post_card_num, $post_dst_address, $post_use_point);

		//주문 공백검사
		for($index = 0; $index < count($register_array); $index++){
			if($register_array[$index] == null){
				alert('공백이 있습니다.');
				exit();
			}
		}

		$now = date("Y-m-d H:i:s");
		$saving_point = ($post_order_total - $post_use_point) / 100;
		$query = "UPDATE auction SET bid_state = '거래종료', company_num = '$register_array[0]', order_date = '$now', order_total = '$register_array[1]', card_num = '$register_array[2]', dst_address = '$register_array[3]', use_point = '$register_array[4]', saving_point = '$saving_point' WHERE auction_num = '$get_auction_num'";
		mysqli_query($conn, $query);
		alert_location('주문이 완료되었습니다.', './auctiondetail.php?num='.$get_auction_num);
	}
?>