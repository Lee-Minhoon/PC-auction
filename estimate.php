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
	<section class="estimate">
		<script type="text/javascript">
			function target_frame(){
				document.form.target = "targetFrame";
				document.form.submit();
			}
			function target_window(){
				document.form.target = "_self";
			}
		</script>
		<div style="width: 990px; height:50px; border-bottom: 1px solid #DEE3EB;">
			<p class="title">견적생성</p>
		</div>
		<form name="form" method="post" action="check.php">
			<div style="margin:40px;">
				<input type="radio" name="purpose" value="사무용" checked="checked" onclick="select_purpose(this.value)" onChange="target_frame()">사무용
				<input type="radio" name="purpose" value="게임용" onclick="select_purpose(this.value)" onChange="target_frame()">게임용
				<input type="radio" name="purpose" value="프로그래밍용" onclick="select_purpose(this.value)" onChange="target_frame()">프로그래밍용
				<input type="radio" name="purpose" value="디자인용" onclick="select_purpose(this.value)" onChange="target_frame()">디자인용
				<input type="radio" name="purpose" value="영상/음향작업용" onclick="select_purpose(this.value)" onChange="target_frame()">영상/음향작업용
				<input type="radio" name="purpose" value="3D작업용" onclick="select_purpose(this.value)" onChange="target_frame()">3D작업용
				<input type="radio" name="purpose" value="서버용/워크스테이션" onclick="select_purpose(this.value)" onChange="target_frame()">서버용/워크스테이션<br>
				<script type="text/javascript">
					function select_purpose(value){
						var div = ['사무용', '게임용', '프로그래밍용', '디자인용', '영상/음향작업용', '3D작업용', '서버용/워크스테이션'];
						for(var index = 0; index < div.length; index++){
							if(value == div[index]){
								document.getElementById(div[index]).style.display = "block";
							}else{
								document.getElementById(div[index]).style.display = "none";
							}
						}
					}
				</script>
				<?php
				$conn = db_connect();
				$parts = ['cpu', 'mainboard', 'main_memory', 'vga', 'power', 'backup_memory', 'case'];
				$purpose = ['사무용', '게임용', '프로그래밍용', '디자인용', '영상/음향작업용', '3D작업용', '서버용/워크스테이션'];
				for($index = 0; $index < count($parts); $index++){
					echo '<select name="'.$parts[$index].'" style="width: 250px; height: 25px;" onChange="target_frame()">';
					echo '<option value="">select '.$parts[$index].'</option>';
					$query = "SELECT * FROM parts WHERE parts_kind = '$parts[$index]'";
					$result = mysqli_query($conn, $query);
					while($row = mysqli_fetch_array($result)){
						$row_parts_name = $row['parts_name'];
						echo "<option value='$row_parts_name'>$row_parts_name</option>";
					}
					echo '</select>';
					echo '<input type="text" name="amount[]" placeholder="개수입력" style="width: 100px; height: 25px;" onChange="target_frame()"><br>';
				}
				for($index = 0; $index <count($purpose); $index++){
					if($index == 0) echo '<div id="'.$purpose[$index].'">';
					else echo '<div id="'.$purpose[$index].'" style="display: none">';
					echo '<select name="program[]" style="width: 250px; height: 25px;" onChange="target_frame()">';
					echo '<option value="미정">프로그램 선택</option>';
					$query = "SELECT * FROM purpose_and_program WHERE purpose = '$purpose[$index]'";
					$result = mysqli_query($conn, $query);
					while($row = mysqli_fetch_array($result)){
						$row_program = $row['program'];
						echo "<option value='$row_program'>$row_program</option>";
					}
					echo '</select>';
					echo '<select name="option[]" style="width: 100px; height: 25px;" onChange="target_frame()">';
					echo '<option value="미정">옵션 선택</option>';
					echo '<option value="최소사양">최소사양</option>';
					echo '<option value="권장사양">권장사양</option>';
					echo '<option value="최고사양">최고사양</option>';
					echo '</select><br>';
					echo '</div>';
				}
				?>
				<iframe name="targetFrame" style="width: 905px; height: 300px;"></iframe><br>
				<button name="register" onclick="target_window()" formaction="estimate.php">견적 저장하기!</button>
			</div>
		</form>
	</section>
</section>
<?php
include "./include/footer.php";
?>

<?php
	if(isset($_POST['register'])){
		estimateRegister();
	}
	
	function estimateRegister(){
		$conn = db_connect();
		$session_number = $_SESSION['session_number'];
		$query = "SELECT * FROM user WHERE user_num = '$session_number'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if($row['user_kind'] != 'client'){
			alert('고객 권한이 필요합니다.');
			exit;
		}
		$post_cpu = $_POST['cpu'];
		$post_mainboard = $_POST['mainboard'];
		$post_main_memory = $_POST['main_memory'];
		$post_vga = $_POST['vga'];
		$post_power = $_POST['power'];
		$post_backup_memory = $_POST['backup_memory'];
		$post_case = $_POST['case'];
		$register_array = array($post_cpu, $post_mainboard, $post_main_memory, $post_vga, $post_power, $post_backup_memory, $post_case);
		$post_amount = $_POST['amount'];
		$row_parts_num = array();
		$subtotal = array();
		$total;

		//Data 가공
		for($index = 0; $index < count($register_array); $index++){
			if($register_array[$index] != null && $post_amount[$index] == null){
				alert('선택한 부품의 개수를 입력해주세요.');
				exit();
			}
			$query = "SELECT * FROM parts WHERE parts_name = '$register_array[$index]'";
			$result = mysqli_query($conn, $query);
			$row = mysqli_fetch_array($result);
			$row_parts_num[$index] = $row['parts_num'];
			$subtotal[$index] = $row['release_price'] * $post_amount[$index];
			$total += $row['release_price'] * $post_amount[$index];
		}
		
		//견적 DB삽입
		$now = date("Y-m-d H:i:s");
		if($total > 0){
			$query = "INSERT INTO estimate (client_num, gen_date, est_total) VALUES ('$session_number', '$now', '$total')";
			mysqli_query($conn, $query);

			//견적 항목 DB삽입
			$last_id = mysqli_insert_id($conn);
			for($index = 0; $index < count($register_array); $index++){
				if($subtotal[$index] > 0){
					$query = "INSERT INTO estimate_item (est_num, parts_num, amount, subtotal) VALUES ('$last_id', '$row_parts_num[$index]', '$post_amount[$index]', '$subtotal[$index]')";
					mysqli_query($conn, $query);
				}
			}
			alert('등록이 완료되었습니다.');
		}else{
			alert('고르신 부품이 없습니다.');
		}
	}
?>