<script src="./include/function.js"></script>
<?php
	include "./include/function.php";
	$conn = db_connect();
	$post_purpose = $_POST['purpose']; //용도
	//각 부품
	$post_cpu = $_POST['cpu'];
	$post_mainboard = $_POST['mainboard'];
	$post_main_memory = $_POST['main_memory'];
	$post_vga = $_POST['vga'];
	$post_power = $_POST['power'];
	$post_backup_memory = $_POST['backup_memory'];
	$post_case = $_POST['case'];
	$register_array = array($post_cpu, $post_mainboard, $post_main_memory, $post_vga, $post_power, $post_backup_memory, $post_case); //부품 배열
	$parts_num_array = array(); //부품 번호 배열 생성
	$parts_price_array = array(); //부품 가격 배열 생성
	$post_amount = $_POST['amount']; //개수 배열
	//부품 종류 배열
	$parts = ['cpu', 'mainboard', 'main_memory', 'vga', 'power', 'backup_memory', 'case'];
	$purpose = ['사무용', '게임용', '프로그래밍용', '디자인용', '영상/음향작업용', '3D작업용', '서버용/워크스테이션']; //용도 종류 배열
	$post_program = $_POST['program']; //프로그래 배열
	$post_option = $_POST['option']; //옵션 배열
	$total;

	for($index = 0; $index < count($register_array); $index++){
		$query = "SELECT * FROM parts WHERE parts_name = '$register_array[$index]'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		array_push($parts_num_array, $row['parts_num']);
		array_push($parts_price_array, $row['release_price']);
		if($parts[$index] == 'cpu'){
			$query = "SELECT * FROM cpu WHERE parts_num = '$parts_num_array[$index]'";
			$result = mysqli_query($conn, $query);
			$row = mysqli_fetch_array($result);
			$cpu_bm_score = $row['cpu_bm_score'];
			$cpu_socket = $row['socket'];
		}
		if($parts[$index] == 'mainboard'){
			$query = "SELECT * FROM mainboard WHERE parts_num = '$parts_num_array[$index]'";
			$result = mysqli_query($conn, $query);
			$row = mysqli_fetch_array($result);
			$mainboard_socket = $row['socket'];
		}
		if($parts[$index] == 'main_memory'){
			$query = "SELECT * FROM main_memory WHERE parts_num = '$parts_num_array[$index]'";
			$result = mysqli_query($conn, $query);
			$row = mysqli_fetch_array($result);
			$main_mem_capacity = $row['main_mem_capacity'];
		}
		if($parts[$index] == 'vga'){
			$query = "SELECT * FROM vga WHERE parts_num = '$parts_num_array[$index]'";
			$result = mysqli_query($conn, $query);
			$row = mysqli_fetch_array($result);
			$vga_bm_score = $row['vga_bm_score'];
		}
	}

	for($index = 0; $index < count($purpose); $index++){
		if($post_purpose == $purpose[$index]){
			echo '용도: '.$purpose[$index].' / ';
			echo '사용할 프로그램: '.$post_program[$index].' / ';
			echo '옵션: '.$post_option[$index].'<br>';
			$query = "SELECT * FROM purpose_and_program WHERE program = '$post_program[$index]' AND purpose_and_program.option = '$post_option[$index]'";
			$result = mysqli_query($conn, $query);
			$row = mysqli_fetch_array($result);
			$need_cpu_bm = $row['cpu_bm_score'];
			$need_ram_capacity = $row['main_mem_capacity'];
			$need_vga_bm = $row['vga_bm_score'];
		}
	}


	for($index = 0; $index < count($register_array); $index++){
		if($register_array[$index] != null){
			echo '==========> ';
			if($post_amount[$index] != null){
				echo $parts[$index].' : '.$register_array[$index];
				echo ' [개수: '.$post_amount[$index].', ';
				$subtotal = $parts_price_array[$index] * $post_amount[$index];
				$total += $subtotal;
				echo '가격: '.$subtotal.']<br>';
			}else{
				echo $parts[$index].' : '.$register_array[$index].'<br>';
			}
		}
	}

	if($cpu_bm_score != null && $need_cpu_bm != null){
		if($cpu_bm_score >= $need_cpu_bm){
			echo 'O / ';
		}else{
			echo 'X / ';
		}
		echo 'cpu의 벤치마크 점수: '.$cpu_bm_score.' / ';
		echo '필요 cpu 벤치마크 점수: '.$need_cpu_bm.'<br>';
	}
	if($main_mem_capacity != null && $need_ram_capacity != null){
		if($main_mem_capacity >= $need_ram_capacity){
			echo 'O / ';
		}else{
			echo 'X / ';
		}
		echo 'main_memory의 용량: '.$main_mem_capacity.' / ';
		echo '필요 main_memory 용량: '.$need_ram_capacity.'<br>';
	}
	if($vga_bm_score != null && $need_vga_bm != null){
		if($vga_bm_score >= $need_vga_bm){
			echo 'O / ';
		}else{
			echo 'X / ';
		}
		echo 'vga의 벤치마크 점수: '.$vga_bm_score.' / ';
		echo '필요 vga 벤치마크 점수: '.$need_vga_bm.'<br>';
	}
	if($mainboard_socket != null && $cpu_socket != null){
		if($mainboard_socket == $cpu_socket ){
			echo 'O / 메인보드와 cpu의 소켓이 일치합니다!<br>';
		}else{
			echo 'X / 메인보드와 cpu의 소켓이 일치하지않습니다!<br>';
		}
	}

	if($total != null){
		echo '총 예상가격: '.$total;
	}
?>