<?php
session_start();
$title = "iT-Village | {$_SESSION['username']}";
include "resources/includes/header.php";

$read_query = "SELECT * FROM users WHERE username = '{$_SESSION['username']}'";
$result_query = mysqli_query($conn, $read_query);
$result_row = mysqli_fetch_assoc($result_query);

if (isset($_POST['throw_dice'])) {
	$dice_num = rand(1, 6);
	$dice_img = "resources/images/dice_".$dice_num.".gif";

	// REFRESH MOVES
	$_SESSION['moves'] -= 1;

	$_SESSION['field'] += $dice_num;
	if ($_SESSION['field'] > 12) {
		$_SESSION['field'] -= 12;
	}

	$field = $_SESSION['field'];

	if ($field == 1 || $field == 12) {
		$_SESSION['event'] = "<span class='underline'>WiFi Pub</span>: You are buying an \"Cloud\" cocktail<br><span class='red'>-5 $</span>";
		$_SESSION['money'] -= 5;
	} elseif ($field == 2 || $field == 7 || $field == 10) {
		if ($_SESSION['money'] > 100) {
			$_SESSION['event'] = "<span class='underline'>Motel</span>: You have enough money and you are buying it!<br><span class='red'>-100 $</span><br><span class='green'>+20 $</span>";
			$_SESSION['money'] -= 80;
			$_SESSION['motels'] += 1;
		} else {
			$_SESSION['event'] = '<span class="underline">Motel</span>: You don\'t have enough money to buy it and you must pay for the stay<br><span class="red">-10 $</span>';
			$_SESSION['money'] -= 10;
		}
	} elseif ($field == 3 || $field == 5 || $field == 8 || $field == 9) {
		$_SESSION['event'] = "<span class='underline'>Freelance Project</span>: You get a payment<br><span class='green'>+20 $</span>";
		$_SESSION['money'] += 20;
	} elseif ($field == 4) {
		$_SESSION['event'] = "<span class='underline'>Storm</span>: There is no WiFi in the village and you are depressed<br><span class='red'>- 2 moves</span>";
		$_SESSION['moves'] -= 2;
	} elseif ($field == 6) {
		$_SESSION['event'] = "<span class='underline'>Super PHP</span>: <span class='green'>Your money is increasing 10 times!</span>";
		$_SESSION['money'] *= 10;
	} elseif ($field == 11) {
		$_SESSION['vso_field'] += 1;
	}

	// conditions for game over
	if ($_SESSION['money'] <= 0) {
		$_SESSION['btn_save_visibility'] = "visibility: hidden;";
		$_SESSION['event'] = '<span class="red">Game over, you don\'t have more money!</span>';

		$played_games = $result_row['played_games'] + 1;
		$defeats = $result_row['defeats'] + 1;

		$update_query = "UPDATE users SET played_games = $played_games, defeats = $defeats WHERE username = '{$_SESSION['username']}'";
		$update_result = mysqli_query($conn, $update_query);

	} elseif ($_SESSION['motels'] == 3) {
		$_SESSION['btn_save_visibility'] = "visibility: hidden;";
		$_SESSION['event'] = "<span class='green'>Congratulations, you buy all motels and own the village!</span>";

		$played_games = $result_row['played_games'] + 1;
		$victories = $result_row['victories'] + 1;

		$update_query = "UPDATE users SET played_games = $played_games, victories = $victories WHERE username = '{$_SESSION['username']}'";
		$update_result = mysqli_query($conn, $update_query);

		header('Location: it-village.php');
	} elseif ($_SESSION['moves'] == 0) {
		$_SESSION['btn_save_visibility'] = "visibility: hidden;";
		$_SESSION['event'] = '<span class="red">Game over, you don\'t have more moves!';

		$played_games = $result_row['played_games'] + 1;
		$defeats = $result_row['defeats'] + 1;

		$update_query = "UPDATE users SET played_games = $played_games, defeats = $defeats WHERE username = '{$_SESSION['username']}'";
		$update_result = mysqli_query($conn, $update_query);
	} elseif ($_SESSION['vso_field'] == 1) {
		$_SESSION['btn_save_visibility'] = "visibility: hidden;";
		$_SESSION['event'] = "<span class='green'>Congratulations, you win with the support of VSO!</span>";
		
		$played_games = $result_row['played_games'] + 1;
		$victories = $result_row['victories'] + 1;

		$update_query = "UPDATE users SET played_games = $played_games, victories = $victories WHERE username = '{$_SESSION['username']}'";
		$update_result = mysqli_query($conn, $update_query);

		header('Location: it-village.php');
	}
	
}
?>
<div class="nav">
	<div class="nav_logo">iT-Village</div>
	<div class="user_to_logo">| <?=$_SESSION['username']?></div>

	<div class="nav_container">
		<form action="" method="post">
			<input style="<?php if(isset($_SESSION['btn_save_visibility'])) { echo $_SESSION['btn_save_visibility']; }?>" class="btn btn_save" type="submit" name="save" value="Save">
			<input class="btn btn_quit" type="submit" name="quit" value="Quit">
		</form>
	</div>
	<div class="nav_container">
		<div class="value_in_nav"><?=$_SESSION['money']?></div>
		<div class="nav_label">Coins:</div>
	</div>
	<div class="nav_container">
		<div class="value_in_nav"><?=$_SESSION['moves']?></div>
		<div class="nav_label">Remaining moves:</div>
	</div>
</div>

<div class="users_panel">
	<div style="background-color: rgba(0, 128, 0,.5); border-bottom: 1.5px solid #008000;">
		<div class="no_label">No</div>
		<div class="username_label">User</div>
		<div class="total_games_label">Total games</div>
		<div class="victories_label">Victories</div>
	</div>	

	<div class="my_info_row">
		<div class="my_no">0</div>
		<div class="my_username"><?=$_SESSION['username']?></div>
		<div class="my_victories"><?=$result_row['victories']?></div>
		<div class="my_total_games"><?=$result_row['played_games']?></div>
	</div>
	<form action="#" method="post">
		<input class="btn btn-link btn_show_all_users" type="submit" name="show_all_users" value="Show all">
	</form>
	<hr>
	<?php
	$read_users_victories = "SELECT username, played_games, victories FROM users WHERE username <> '".$_SESSION['username']."' ORDER BY victories DESC";
	if (!isset($_POST['show_all_users'])) {
		$read_users_victories .= " LIMIT 10";
	}
	
	$result_users_victories = mysqli_query($conn, $read_users_victories);
	$n = 1;
	while ($row = mysqli_fetch_assoc($result_users_victories)) {
		echo "<div class='user_info_row'>";
		echo '<div class="user_no">'.$n.'</div>';
		echo '<div class="user_username">'.$row['username'].'</div>';
		echo '<div class="user_victories">'.$row['victories'].'</div>';
		echo '<div class="user_total_games">'.$row['played_games'].'</div>';
		echo "</div>";
		$n++;
	}
	?>
</div>

<div class="game_panel">
	<table>
		<tr>
			<td class="<?php if ($_SESSION['field'] == 1) { echo 'current_td'; }?>">&#x1f37a;</td>
			<td class="<?php if ($_SESSION['field'] == 2) { echo 'current_td'; }?>">&#127968;</td>
			<td class="<?php if ($_SESSION['field'] == 3) { echo 'current_td'; }?>">&#x24;</td>
			<td class="<?php if ($_SESSION['field'] == 4) { echo 'current_td'; }?>">&#x2601;</td>
		</tr>
		<tr>
			<td class="<?php if ($_SESSION['field'] == 12) { echo 'current_td'; }?>">&#x1f37a;</td>
			<td class="middle_td" colspan="2"></td>
			<td class="<?php if ($_SESSION['field'] == 5) { echo 'current_td'; }?>">&#x24;</td>
		</tr>
		<tr>
			<td class="small_font_vso <?php if ($_SESSION['field'] == 11) { echo 'current_td'; }?>">VSO</td>
			<td class="middle_td" colspan="2"></td>
			<td class="small_font_x10 <?php if ($_SESSION['field'] == 6) { echo 'current_td'; }?>">x10</td>
		</tr>
		<tr>
			<td class="<?php if ($_SESSION['field'] == 10) { echo 'current_td'; }?>">&#127968;</td>
			<td class="<?php if ($_SESSION['field'] == 9) { echo 'current_td'; }?>">&#x24;</td>
			<td class="<?php if ($_SESSION['field'] == 8) { echo 'current_td'; }?>">&#x24;</td>
			<td class="<?php if ($_SESSION['field'] == 7) { echo 'current_td'; }?>">&#127968;</td>
		</tr>
	</table>
</div>

<div class="event_panel">
	<div class="event_label">Events:</div>
	<div class="event_window"><?=$_SESSION['event']?>
	</div>
</div>

<div class="dice_panel">
	<form action="" method="post">
		<input class="btn btn-lignt btn_throw_dice" type="submit" name="throw_dice" value="THROW THE DICE">
	</form>
	<div class="dice_img_area"><?php
		if (isset($dice_img)) {
			echo '<img class="dice_img" src="'.$dice_img.'" alt="dice" width="200px">';
		}?>
	</div>
</div>
<?php
if (isset($_POST['save'])) {
	// UPDATE WITH SESSION VALUES
	//
	session_destroy();
	header('Location: index.php');
}
include 'resources/includes/footer.php'; ?>
