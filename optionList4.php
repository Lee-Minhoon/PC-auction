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
	<section class="optionList">
		<div style="width: 990px; height:50px; border-bottom: 1px solid #DEE3EB;">
			<ul class="ul1" style="float: left; margin-left: 40px; padding: 0px;">
				<a href="optionList.php">프로그램목록</a>
				<ul class="ul2">
					<li><a href="optionList1.php">사무용</a></li>
					<li><a href="optionList2.php">게임용</a></li>
					<li><a href="optionList3.php">프로그래밍용</a></li>
					<li><a href="optionList4.php">디자인용</a></li>
					<li><a href="optionList5.php">영상/음향작업용</a></li>
					<li><a href="optionList6.php">3D작업용</a></li>
					<li><a href="optionList7.php">서버용/워크스테이션</a></li>
				</ul>
			</ul>
			<p class="title">디자인용</p>
			<ul><a href="optionRegister.php" style="float: right; margin-right: 50px;">+프로그램등록</a></ul>
		</div>
		<style type="text/css">
			.perLine { width: 990px; border-top: 1px solid #DEE3EB; }
			.head { font-weight: bold; margin-top: 5px; margin-bottom: 5px; }
			.block1 { width: 170px; margin-left: 40px; }
			.block2 { width: 260px; }
			.block3 { width: 100px; }
			.block4 { width: 140px; }
			.block5 { width: 140px; }
			.block6 { width: 140px; }
		</style>
		<div>
			<div class="block1"><p class="head">용도</p></div>
			<div class="block2"><p class="head">프로그램</p></div>
			<div class="block3"><p class="head">옵션</p></div>
			<div class="block4"><p class="head">CPU벤치마크</p></div>
			<div class="block5"><p class="head">메모리용량</p></div>
			<div class="block6"><p class="head">VGA벤치마크</p></div>
			<?php
			$conn = db_connect();
			$session_id = $_SESSION['session_id'];
			$query = "SELECT * FROM user WHERE id = '$session_id'";
			$result = mysqli_query($conn, $query);
			$row = mysqli_fetch_array($result);
			if($row['user_kind'] != 'admin'){
				alert_back('관리자 권한이 필요합니다.');
				exit();
			}

			$query = "SELECT * FROM purpose_and_program WHERE purpose = '디자인용'";
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
			$query = "SELECT * FROM purpose_and_program WHERE purpose = '디자인용' ORDER BY purpose DESC LIMIT $start_point, $list"; //첫번째 게시글부터 한페이지에 보여줄 게시글 갯수만큼 들고옴
			$result = mysqli_query($conn, $query);

			for($index = 1; $index <= $list; $index++){ //한페이지에 들어가는 게시글 갯수만큼 들고옴
				$rows = mysqli_fetch_array($result);
				if($rows != null){
			?>
					<div class="perLine">
						<div class="block1"><?= $rows['purpose'] ?></div>
						<div class="block2"><?= $rows['program'] ?></div>
						<div class="block3"><?= $rows['option'] ?></div>
						<div class="block4"><?= $rows['cpu_bm_score'] ?></div>
						<div class="block5"><?= $rows['main_mem_capacity'] ?></div>
						<div class="block6"><?= $rows['vga_bm_score'] ?></div>
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
		</div>
		<?php
		}
		?>
	</section>
</section>
<?php
include "./include/footer.php";
?>