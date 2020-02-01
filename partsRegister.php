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
				exit();
			}
			?>
		</section>
		<?php include "./include/leftmenu.php"; ?>
	</section>
	<section class="partsRegister">
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
			<p class="title">부품등록</p>
		</div>
		<style type="text/css">
			.inputBox { width: 250px; height: 25px; }
		</style>
		<form method="post" action="partsRegister.php">
			<div style="margin: 40px;">
				<input type="radio" name="parts_kind" value="cpu" checked="checked" onclick="parts_kind_div(this.value)">CPU
				<input type="radio" name="parts_kind" value="mainboard" onclick="parts_kind_div(this.value)">메인보드
				<input type="radio" name="parts_kind" value="main_memory" onclick="parts_kind_div(this.value)">메모리
				<input type="radio" name="parts_kind" value="vga" onclick="parts_kind_div(this.value)">그래픽카드
				<input type="radio" name="parts_kind" value="power" onclick="parts_kind_div(this.value)">파워
				<input type="radio" name="parts_kind" value="backup_memory" onclick="parts_kind_div(this.value)">보조기억장치
				<input type="radio" name="parts_kind" value="case" onclick="parts_kind_div(this.value)">케이스<br>
				<script type="text/javascript">
					function parts_kind_div(kind){
						var string = "kind_" + kind;
						var div = ["kind_cpu", "kind_mainboard", "kind_main_memory", "kind_vga", "kind_power", "kind_backup_memory", "kind_case"];
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
					<input class="inputBox" type="text" name="parts_name" placeholder="부품이름"><br>
					<input class="inputBox" type="text" name="making_company" placeholder="제조사"><br>
					<input class="inputBox" type="text" name="release_date" placeholder="출시일"><br>
					<input class="inputBox" type="text" name="release_price" placeholder="출시가"><br>
					<div id="kind_cpu">
						<input class="inputBox" type="text" name="core" placeholder="코어"><br>
						<input class="inputBox" type="text" name="core_kind" placeholder="코어 종류"><br>
						<input class="inputBox" type="text" name="clock" placeholder="클럭"><br>
						<input class="inputBox" type="text" name="socket" placeholder="소켓"><br>
						<input class="inputBox" type="text" name="cpu_bm_score" placeholder="벤치마크 점수">
					</div>
					<div id="kind_mainboard" style="display: none">
						<input class="inputBox" type="text" name="std" placeholder="규격"><br>
						<input class="inputBox" type="text" name="mb_socket" placeholder="소켓"><br>
						<input class="inputBox" type="text" name="main_mem_kind" placeholder="메모리 종류"><br>
						<input class="inputBox" type="text" name="main_mem_slot" placeholder="메모리 슬롯"><br>
						<input class="inputBox" type="text" name="vga_slot" placeholder="그래픽카드 슬롯">
					</div>
					<div id="kind_main_memory" style="display: none">
						<input class="inputBox" type="text" name="main_mem_mode" placeholder="방식"><br>
						<input class="inputBox" type="text" name="main_mem_capacity" placeholder="용량">
					</div>
					<div id="kind_vga" style="display: none">
						<input class="inputBox" type="text" name="interface" placeholder="인터페이스"><br>
						<input class="inputBox" type="text" name="vga_bm_score" placeholder="벤치마크 점수">
					</div>
					<div id="kind_power" style="display: none">
						<input class="inputBox" type="text" name="power_form" placeholder="파워 형식"><br>
						<input class="inputBox" type="text" name="rated_output" placeholder="정격 출력">
					</div>
					<div id="kind_backup_memory" style="display: none">
						<input class="inputBox" type="text" name="backup_mem_kind" placeholder="종류"><br>
						<input class="inputBox" type="text" name="backup_mem_capacity" placeholder="용량">
					</div>
					<div id="kind_case" style="display: none">
						<input class="inputBox" type="text" name="mainboard_std" placeholder="메인보드 규격"><br>
						<input class="inputBox" type="text" name="power_std" placeholder="파워 규격">
					</div>
				</div>
				<div>
					<input type="submit" name="submit" value="완료">
					<button type="button" onclick="location.href = './index.php'">취소</button>
				</div>
			</div>
		</form>
	</section>
</section>
<?php
include "./include/footer.php";
?>

<?php
	if(isset($_POST['submit'])){
		parts_Register();
	}
	
	function parts_Register(){
		$conn = db_connect();
		$session_id = $_SESSION['session_id'];
		$query = "SELECT * FROM user WHERE id = '$session_id'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if($row['user_kind'] != 'admin'){
			alert_back('관리자 권한이 필요합니다.');
			exit();
		}
		$post_parts_kind = $_POST['parts_kind'];
		$post_parts_name = $_POST['parts_name'];
		$post_making_company = $_POST['making_company'];
		$post_release_date = $_POST['release_date'];
		$post_release_price = $_POST['release_price'];
		$register_array = array($post_parts_kind, $post_parts_name, $post_making_company, $post_release_date, $post_release_price);
		if($post_parts_kind == "cpu"){
			$post_core = $_POST['core'];
			$post_core_kind = $_POST['core_kind'];
			$post_clock = $_POST['clock'];
			$post_socket = $_POST['socket'];
			$post_cpu_bm_score = $_POST['cpu_bm_score'];
			array_push($register_array, $post_core, $post_core_kind, $post_clock, $post_socket, $post_cpu_bm_score);
		}else if($post_parts_kind == "mainboard"){
			$post_std = $_POST['std'];
			$post_mb_socket = $_POST['mb_socket'];
			$post_main_mem_kind = $_POST['main_mem_kind'];
			$post_main_mem_slot = $_POST['main_mem_slot'];
			$post_vga_slot = $_POST['vga_slot'];
			array_push($register_array, $post_std, $post_mb_socket, $post_main_mem_kind, $post_main_mem_slot, $post_vga_slot);
		}else if($post_parts_kind == "main_memory"){
			$post_main_mem_mode = $_POST['main_mem_mode'];
			$post_main_mem_capacity = $_POST['main_mem_capacity'];
			array_push($register_array, $post_main_mem_mode, $post_main_mem_capacity);
		}else if($post_parts_kind == "vga"){
			$post_interface = $_POST['interface'];
			$post_vga_bm_score = $_POST['vga_bm_score'];
			array_push($register_array, $post_interface, $post_vga_bm_score);
		}else if($post_parts_kind == "power"){
			$post_power_form = $_POST['power_form'];
			$post_rated_output = $_POST['rated_output'];
			array_push($register_array, $post_power_form, $post_rated_output);
		}else if($post_parts_kind == "backup_memory"){
			$post_backup_mem_kind = $_POST['backup_mem_kind'];
			$post_backup_mem_capacity = $_POST['backup_mem_capacity'];
			array_push($register_array, $post_backup_mem_kind, $post_backup_mem_capacity);
		}else{
			$post_mainboard_std = $_POST['mainboard_std'];
			$post_power_std = $_POST['power_std'];
			array_push($register_array, $post_mainboard_std, $post_power_std);
		}

		//부품 공백검사
		for($index = 0; $index < count($register_array); $index++){
			if($register_array[$index] == null){
				alert('공백이 있습니다.');
				exit();
			}
		}

		//부품 중복검사
		$query = "SELECT * FROM parts WHERE parts_name = '$post_parts_name'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if(isset($row)){
			alert_location('존재하는 부품입니다.', './partsRegister.php');
			exit();
		}

		//부품 DB삽입
		$query = "INSERT INTO parts (parts_kind, parts_name, making_company, release_date, release_price) VALUES ('$register_array[0]', '$register_array[1]', '$register_array[2]', '$register_array[3]', '$register_array[4]')";
		mysqli_query($conn, $query);

		$last_id = mysqli_insert_id($conn);
		//부품 타입에 따른 DB삽입
		if($post_parts_kind == "cpu"){
			$query = "INSERT INTO cpu (parts_num, core, core_kind, clock, socket, cpu_bm_score) VALUES ('$last_id', '$register_array[5]', '$register_array[6]', '$register_array[7]', '$register_array[8]', '$register_array[9]')";
			mysqli_query($conn, $query);
		}else if($post_parts_kind == "mainboard"){
			$query = "INSERT INTO mainboard (parts_num, std, socket, main_mem_kind, main_mem_slot, vga_slot) VALUES ('$last_id', '$register_array[5]', '$register_array[6]', '$register_array[7]', '$register_array[8]', '$register_array[9]')";
			mysqli_query($conn, $query);
		}else if($post_parts_kind == "main_memory"){
			$query = "INSERT INTO main_memory (parts_num, main_mem_mode, main_mem_capacity) VALUES ('$last_id', '$register_array[5]', '$register_array[6]')";
			mysqli_query($conn, $query);
		}else if($post_parts_kind == "vga"){
			$query = "INSERT INTO vga (parts_num, interface, vga_bm_score) VALUES ('$last_id', '$register_array[5]', '$register_array[6]')";
			mysqli_query($conn, $query);
		}else if($post_parts_kind == "power"){
			$query = "INSERT INTO power (parts_num, power_form, rated_output) VALUES ('$last_id', '$register_array[5]', '$register_array[6]')";
			mysqli_query($conn, $query);
		}else if($post_parts_kind == "backup_memory"){
			$query = "INSERT INTO backup_memory (parts_num, backup_mem_kind, backup_mem_capacity) VALUES ('$last_id', '$register_array[5]', '$register_array[6]')";
			mysqli_query($conn, $query);
		}else{
			$query = "INSERT INTO pcdb.case (parts_num, mainboard_std, power_std) VALUES ('$last_id', '$register_array[5]', '$register_array[6]')";
			mysqli_query($conn, $query);
		}
		alert('등록이 완료되었습니다.');
	}
?>