<?php
$title = "Do you want to continue the last game?";
include "resources/includes/header.php";
session_start();
$username = $_SESSION['username'];
?>
<div class="game_name">iT-Village</div>
<div style="width: 35%;" class="log_panel">
	<div class="form_heading"><span class="green"><?=$username?></span>,<br>Do you want to continue the last game?</div>

	<form action="" method="post">
		<input class="btn btn_log btn_continue_yes" type="submit" name="yes" value="YES">
		<input class="btn btn_log btn_continue_no" type="submit" name="no" value="NO - Start a new game">
	</form>
	<div class="text_under_log_btn">iT-Village, 2019</div>
</div>

<?php
if (isset($_POST['yes'])) {
	$read_query = "SELECT last_money, remaining_moves, last_field, motels FROM users WHERE username = '$username'";
	$read_result = mysqli_query($conn, $read_query);
	$row = mysqli_fetch_assoc($read_result);

	$_SESSION['money'] = $row['last_money'];
	$_SESSION['field'] = $row['last_field'];
	$_SESSION['moves'] = $row['remaining_moves'];
	$_SESSION['motels'] = $row['motels'];
	$_SESSION['vso_field'] = 0;

	$_SESSION['event'] = '<span class="green">This is the last game you don\'t finish.</span><br>Let\'s do it this time!';

	header('Location: it-village.php');
}

if (isset($_POST['no'])) {
	$update_query = "UPDATE users SET in_progress = 'NO', last_money = NULL, remaining_moves = NULL, last_field = NULL, motels = NULL WHERE username = '$username'";
	$update_result = mysqli_query($conn, $update_query);

	$field = rand(1, 12);
	$moves = rand(5, 10);
	$_SESSION['money'] = 50;
	$_SESSION['field'] = $field;
	$_SESSION['moves'] = $moves;
	$_SESSION['motels'] = 0;
	$_SESSION['vso_field'] = 0;

	$_SESSION['event'] = "<span style='font-size: 1.55vw;'>Welcome again, <span class='green'>$username</span>!<br>"."This time you have <span class='green'>$moves moves</span> and the initial field is <span class='green'>$field</span>.<br><br>".'Let\'s play!</span>';

	header('Location: it-village.php');
}

include 'resources/includes/footer.php';
?>