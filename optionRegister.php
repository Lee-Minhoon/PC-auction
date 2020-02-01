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
			if($row['user_kind'] != 'admin'){
				alert_back('관리자 권한이 필요합니다.');
			}
			?>
		</section>
		<?php include "./include/leftmenu.php"; ?>
	</section>
	<section class="optionRegister">
		<div style="width: 990px; height:50px; border-bottom: 1px solid #DEE3EB;">
			<ul class="ul1" style="float: left; margin-left: 40px; padding: 0px;">
				<a href="optionList.php">프로그램목록</a>
				<ul class="ul2">
					<li><a href="partsCPU.php">사무용</a></li>
					<li><a href="partsMainboard.php">게임용</a></li>
					<li><a href="partsMainMemory.php">프로그래밍용</a></li>
					<li><a href="partsVGA.php">디자인용</a></li>
					<li><a href="partsPower.php">영상/음향작업용</a></li>
					<li><a href="partsBackupMemory.php">3D작업용</a></li>
					<li><a href="partsCase.php">서버용/워크스테이션</a></li>
				</ul>
			</ul>
			<p class="title">프로그램등록</p>
		</div>
		<style type="text/css">
			.inputBox { width: 250px; height: 25px; }
		</style>
		<form method="post" action="optionRegister.php">
			<div style="margin: 40px;">
				<input type="radio" name="purpose" value="사무용" checked="checked">사무용
				<input type="radio" name="purpose" value="게임용">게임용
				<input type="radio" name="purpose" value="프로그래밍용">프로그래밍용
				<input type="radio" name="purpose" value="디자인용">디자인용
				<input type="radio" name="purpose" value="영상/음향작업용">영상/음향작업용
				<input type="radio" name="purpose" value="3D작업용">3D작업용
				<input type="radio" name="purpose" value="서버용/워크스테이션">서버용/워크스테이션<br>
				<input class="inputBox" type="text" name="program" placeholder="프로그램"><br>
				<select class="inputBox" name="option">
					<option value="">옵션</option>
					<option value="최소사양">최소사양</option>
					<option value="권장사양">권장사양</option>
					<option value="최고사양">최고사양</option>
				</select><br>
				<input class="inputBox" type="text" name="cpu_bm_score" placeholder="CPU벤치마크"><br>
				<input class="inputBox" type="text" name="main_mem_capacity" placeholder="메모리용량"><br>
				<input class="inputBox" type="text" name="vga_bm_score" placeholder="VGA벤치마크"><br>
				<input type="submit" name="submit" value="완료">
				<button type="button" onclick="location.href = './index.php'">취소</button>
			</div>
		</form>
	</section>
</section>
<?php
include "./include/footer.php";
?>

<?php
	if(isset($_POST['submit'])){
		optionRegister();
	}
	
	function optionRegister(){
		$conn = db_connect();
		$session_id = $_SESSION['session_id'];
		$query = "SELECT * FROM user WHERE id = '$session_id'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if($row['user_kind'] != 'admin'){
			alert_back('관리자 권한이 필요합니다.');
			exit();
		}
		$post_purpose = $_POST['purpose'];
		$post_program = $_POST['program'];
		$post_option = $_POST['option'];
		$post_cpu_bm_score = $_POST['cpu_bm_score'];
		$post_main_mem_capacity = $_POST['main_mem_capacity'];
		$post_vga_bm_score = $_POST['vga_bm_score'];
		$register_array = array($post_purpose, $post_program, $post_option, $post_cpu_bm_score, $post_main_mem_capacity, $post_vga_bm_score);

		//용도 공백검사
		for($index = 0; $index < count($register_array); $index++){
			if($register_array[$index] == null){
				alert('공백이 있습니다.');
				exit();
			}
		}

		//용도 중복검사
		$query = "SELECT * FROM purpose_and_program WHERE program = '$post_program' AND purpose_and_program.option = '$post_option'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if(isset($row)){
			alert_location('존재하는 프로그램입니다.', './optionRegister.php');
			exit();
		}

		//용도 DB삽입
		$query = "INSERT INTO purpose_and_program (purpose, program, purpose_and_program.option, cpu_bm_score, main_mem_capacity, vga_bm_score) VALUES ('$register_array[0]', '$register_array[1]', '$register_array[2]', '$register_array[3]', '$register_array[4]', '$register_array[5]')";
		mysqli_query($conn, $query);
		alert('등록이 완료되었습니다.');
	}
?>