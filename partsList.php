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
	<section class="partsList">
		<form name="thisForm" action="deleteParts.php" method="post" onsubmit="return checkbox(this)">
			<div style="width: 990px; height:50px; border-bottom: 1px solid #DEE3EB;">
				<ul class="ul1" style="float: left; margin-left: 40px; padding: 0px;">
					<a href="partsList.php">부품목록</a>
					<ul class="ul2">
						<li><a href="partsCPU.php">CPU</a></li>
						<li><a href="partsMainboard.php">메인보드</a></li>
						<li><a href="partsMainMemory.php">메모리</a></li>
						<li><a href="partsVGA.php">그래픽카드</a></li>
						<li><a href="partsPower.php">파워</a></li>
						<li><a href="partsBackupMemory.php">보조기억장치</a></li>
						<li><a href="partsCase.php">케이스</a></li>
					</ul>
				</ul>
				<p class="title">부품목록</p>
				<ul><a href="partsRegister.php" style="float: right; margin-right: 50px;">+부품등록</a></ul>
				<ul><a onclick="javascript: checkbox();" style="float: right; margin-right: 50px; cursor: pointer;">+부품삭제</a></ul>
			</div>
			<script type="text/javascript">
				function checkbox(){
					var form = document.thisForm;
					var length = form.elements['checkbox[]'].length;
					if(typeof(length) == 'undefined'){
						if (form.elements['checkbox[]'].checked == true){
							form.elements['hidden[]'].value = 1;
						}
					}
					for(i = 0; i < length; i++){
						if (form.elements['checkbox[]'][i].checked == true){
							form.elements['hidden[]'][i].value = 1;
						}
					}
					thisForm.submit();
				}
			</script>
			<style type="text/css">
				.perLine { width: 990px; border-top: 1px solid #DEE3EB; }
				.head { font-weight: bold; margin-top: 5px; margin-bottom: 5px; }
				.checkBox { width: 10px; margin-left: 10px; }
				.block1 { width: 90px; margin-left: 30px; }
				.block2 { width: 140px; }
				.block3 { width: 230px; }
				.block4 { width: 140px; }
				.block5 { width: 200px; }
				.block6 { width: 140px; }
			</style>
			<div>
				<div class="checkBox">　</div>
				<div class="block1"><p class="head">부품 번호</p></div>
				<div class="block2"><p class="head">부품 종류</p></div>
				<div class="block3"><p class="head">부품 이름</p></div>
				<div class="block4"><p class="head">제조사</p></div>
				<div class="block5"><p class="head">출시일</p></div>
				<div class="block6"><p class="head">출시가</p></div><br>
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

				$query = "SELECT * FROM parts";
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
				$query = "SELECT * FROM parts ORDER BY parts_num DESC LIMIT $start_point, $list"; //첫번째 게시글부터 한페이지에 보여줄 게시글 갯수만큼 들고옴
				$result = mysqli_query($conn, $query);

				for($index = 1; $index <= $list; $index++){ //한페이지에 들어가는 게시글 갯수만큼 들고옴
					$rows = mysqli_fetch_array($result);
					if($rows != null){
				?>
						<div class="perLine">
							<div class="checkBox"><input type="checkbox" name="checkbox[]"></div>
							<input type="hidden" name="parts[]" value="<?= $rows['parts_kind'] ?>">
							<input type="hidden" name="hidden[]" value="0">
							<input type="hidden" name="start_point" value="<?=$start_point?>">
							<input type="hidden" name="list" value="<?=$list?>">
							<div class="block1"><?= $rows['parts_num'] ?></div>
							<div class="block2"><?= $rows['parts_kind'] ?></div>
							<div class="block3"><?= $rows['parts_name'] ?></div>
							<div class="block4"><?= $rows['making_company'] ?></div>
							<div class="block5"><?= $rows['release_date'] ?></div>
							<div class="block6"><?= $rows['release_price'] ?></div>
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
				<
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