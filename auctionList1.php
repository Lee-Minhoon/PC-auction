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
	<section class="auctionList">
		<div style="width: 990px; height:50px; border-bottom: 1px solid #DEE3EB;">
			<ul class="ul1" style="float: left; margin-left: 40px; padding: 0px;">
				<a href="auctionList.php">역경매목록</a>
				<ul class="ul2">
					<li><a href="auctionList1.php">진행중</a></li>
					<li><a href="auctionList2.php">마감</a></li>
					<li><a href="auctionList3.php">거래종료</a></li>
				</ul>
			</ul>
			<p class="title">역경매목록</p>
			<ul><a href="auctionRegister.php" style="float: right; margin-right: 50px;">+역경매등록</a></ul>
		</div>
		<style type="text/css">
			.perLine { width: 990px; border-top: 1px solid #DEE3EB; }
			.head { font-weight: bold; margin-top: 5px; margin-bottom: 5px; }
			.block1 { width: 50px; margin-left: 40px; }
			.block2 { width: 100px; }
			.block3 { width: 220px; }
			.block4 { width: 190px; }
			.block5 { width: 190px; }
			.block6 { width: 100px; }
			.block7 { width: 100px; }
		</style>
		<div>
			<div class="block1"><p class="head">번호</p></div>
			<div class="block2"><p class="head">등록</p></div>
			<div class="block3"><p class="head">제목</p></div>
			<div class="block4"><p class="head">시작시간</p></div>
			<div class="block5"><p class="head">마감시간</p></div>
			<div class="block6"><p class="head">상태</p></div>
			<div class="block7"><p class="head">입찰건수</p></div><br>
			<?php
			$conn = db_connect();
			$session_id = $_SESSION['session_id'];

			$query = "SELECT * FROM auction WHERE bid_state = '진행중'";
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
			$query = "SELECT * FROM auction WHERE bid_state = '진행중' ORDER BY auction_num DESC LIMIT $start_point, $list"; //첫번째 게시글부터 한페이지에 보여줄 게시글 갯수만큼 들고옴
			$result = mysqli_query($conn, $query);

			for($index = 1; $index <= $list; $index++){ //한페이지에 들어가는 게시글 갯수만큼 들고옴
				$rows = mysqli_fetch_array($result);
				$auction_num = $rows['auction_num'];
				$query = "SELECT * FROM bid WHERE auction_num = '$auction_num'";
				$bid = mysqli_query($conn, $query);
				$bid = mysqli_num_rows($bid);
				$client_num = $rows['client_num'];
				$query = "SELECT * FROM user WHERE user_num = '$client_num'";
				$client = mysqli_query($conn, $query);
				$client = mysqli_fetch_array($client);
				$client = $client['id'];
				if($rows != null){
			?>
					<div class="perLine">
						<div class="block1"><?= $rows['auction_num'] ?></div>
						<div class="block2"><?= $client ?></div>
						<div class="block3">
							<a href="auctionDetail.php?num=<?= $rows['auction_num'] ?>"><?= $rows['auction_title'] ?></a>
						</div>
						<div class="block4"><?= $rows['regi_time'] ?></div>
						<div class="block5"><?= $rows['dead_time'] ?></div>
						<div class="block6"><?= $rows['bid_state'] ?></div>
						<div class="block7"><?= $bid ?></div><br>
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
	</section>
</section>
<?php
include "./include/footer.php";
?>