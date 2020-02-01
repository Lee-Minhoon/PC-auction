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

		$query = "SELECT * FROM auction WHERE auction_num = '$get_auction_num'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		$est_num = $row['est_num'];
		?>
		<div style="width: 990px; height:50px; border-bottom: 1px solid #DEE3EB;">
			<ul class="ul1" style="float: left; margin-left: 40px; padding: 0px;">
				<a href="auctionList.php">목록보기</a>
			</ul>
			<p class="title"><?= $row['auction_title'] ?></p>
			<ul><a href="bidRegister.php?num=<?= $get_auction_num ?>" style="float: right; margin-right: 50px;">+입찰하기</a></ul>
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
		<style type="text/css">
			.perLine { width: 990px; border-top: 1px solid #DEE3EB; }
			.head { font-weight: bold; margin-top: 5px; margin-bottom: 5px; }
			.block1 { width: 80px; margin-left: 40px; }
			.block2 { width: 50px; }
			.block3 { width: 820px; }
		</style>
		<form method="post" action="order.php?num=<?= $get_auction_num ?>">
			<div>
				<div class="block1"><p class="head">　</p></div>
				<div class="block2"><p class="head">입찰</p></div>
				<div class="block3"><p class="head">내용</p></div><br>
				<?php
				$conn = db_connect();
				$get_auction_num = $_GET['num'];
				$session_id = $_SESSION['session_id'];
				$session_num = $_SESSION['session_number'];

				$query = "SELECT * FROM bid WHERE auction_num = '$get_auction_num'";
				$result = mysqli_query($conn, $query);
				$data = mysqli_num_rows($result); //총 데이터의 개수

				$page = ($_GET['page'])?$_GET['page']:1;
				$list = 20; //한 페이지에 보여줄 데이터의 개수
				$block = 10; //한 블럭에 보여줄 페이지의 개수

				$pageNum = ceil($data/$list); //전체 페이지를 구함
				$blockNum = ceil($pageNum/$block); //전체 블럭을 구함
				$now_block = ceil($page/$block); //현재 블럭을 구함

				$start_page = ($now_block * $block) - ($block - 1); //현재 블럭에서 표시될 첫번째 페이지
				if($start_page <= 1) $start_page = 1;

				$end_page = $now_block * $block; //현재 블럭에서 표시될 마지막 페이지
				if($pageNum <= $end_page) $end_page = $pageNum;

				$start_point = ($page - 1) * $list; //현재 페이지에서 뿌려줄 첫번째 게시글
				$query = "SELECT * FROM bid WHERE auction_num = '$get_auction_num' ORDER BY bid_time DESC LIMIT $start_point, $list"; //첫번째 게시글부터 한페이지에 보여줄 게시글 갯수만큼 들고옴
				$result = mysqli_query($conn, $query);

				for($index = 1; $index <= $list; $index++){ //한페이지에 들어가는 게시글 갯수만큼 들고옴
					$rows = mysqli_fetch_array($result);
					$company_num = $rows['company_num'];
					$query = "SELECT * FROM user WHERE user_num = '$company_num'";
					$company = mysqli_query($conn, $query);
					$company = mysqli_fetch_array($company);
					$company = $company['id'];
					$query = "SELECT * FROM auction WHERE auction_num = '$get_auction_num'";
					$bid = mysqli_query($conn, $query);
					$bid = mysqli_fetch_array($bid);
					$y_bid = $bid['company_num'];
					if($rows != null && $bid['bid_state'] == '진행중' && $bid['client_num'] == $session_num){
				?>
						<div class="perLine">
							<div class="block1"><button type="submit" name="company_num" value="<?= $rows['company_num'] ?>">낙찰</button></div>
							<div class="block2">
								<a href="auctionDetail.php?num=<?= $rows['company_num'] ?>"><?= $company ?></a>
							</div>
							<div class="block3"><?= $rows['bid_content'] ?></div><br>
						</div>
				<?php
					}else if($rows != null){
				?>
						<div class="perLine">
				<?php
						if($y_bid == $rows['company_num']){
				?>
							<div class="block1">낙찰</div>
				<?php
						}else{
				?>
							<div class="block1">　</div>
				<?php
						}
				?>
							
							<div class="block2">
								<a href="auctionDetail.php?num=<?= $rows['company_num'] ?>"><?= $company ?></a>
							</div>
							<div class="block3"><?= $rows['bid_content'] ?></div><br>
						</div>
				<?php
					}
				}
				?>
			</div>
			<div class="pageList">
			<?php
			if($start_page - 1 > 0){
			?>
				<a href="<?php echo $_SERVER['PHP_SELF']?>?page=<?=$start_page-1?>">이전</a>
			<?php
			}
			for($p = $start_page; $p <= $end_page; $p++){ //첫번째 페이지부터 마지막 페이지까지 표시함
				if($p == $page){
			?>
					<a class="clickPage"><?=$p?></a>
			<?php
				}else{
			?>
					<a href="<?php echo $_SERVER['PHP_SELF']?>?page=<?=$p?>"><?=$p?></a>	
			<?php
				}
			}
			if($end_page + 1 <= $pageNum){
			?>
				<a href="<?php echo $_SERVER['PHP_SELF']?>?page=<?=$end_page+1?>">다음</a>
			<?php
			}
			?>
			</div>
		</form>
	</section>
</section>
<?php
include "./include/footer.php";
?>