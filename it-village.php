<?php
session_start();
$username = $_SESSION['username'];
$title = "iT-Village | $username";
include "resources/includes/header.php";

function new_game($conn, $username, $moves, $field) {
	$clear_in_progress = "UPDATE users SET in_progress = 'NO', last_money = NULL, remaining_moves = NULL, last_field = NULL, motels = NULL WHERE username = '$username'";
	$clear_result = mysqli_query($conn, $clear_in_progress);

	$_SESSION['money'] = 50;
	$_SESSION['event'] .= "<br>New game, <span class='underline'>$moves moves and initial field $field</span>";
	$_SESSION['moves'] = $moves;
	$_SESSION['field'] = $field;
	$_SESSION['motels'] = 0;
	$_SESSION['vso_field'] = 0;

	header('Location: it-village.php');
}

$read_query = "SELECT * FROM users WHERE username = '$username'";
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
		$_SESSION['event'] = "<span class='underline'>WiFi Pub</span>: You are buying an \"Cloud\" cocktail<br><span class='red'>-5 Coins</span>";
		$_SESSION['money'] -= 5;
	} elseif ($field == 2 || $field == 7 || $field == 10) {
		if ($_SESSION['money'] > 100) {
			$_SESSION['event'] = "<span class='underline'>Motel</span>: You have enough money and you are buying it!<br><span class='red'>-80 $</span>";
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
		$_SESSION['event'] = "<span class='underline'>Storm</span>: There is no WiFi in the village and you are depressed, <span class='red'>you skip 2 fields</span>!";
		$_SESSION['field'] += 2;
	} elseif ($field == 6) {
		$_SESSION['event'] = "<span class='underline'>Super PHP</span>: <span class='green'>Your money is increasing 10 times!</span>";
		$_SESSION['money'] *= 10;
	} elseif ($field == 11) {
		$_SESSION['vso_field'] += 1;
	}

	$played_games = $result_row['played_games'];
	$victories = $result_row['victories'];
	$defeats = $result_row['defeats'];

	// CONDITIONS FOR END OF THE GAME
	if ($_SESSION['money'] <= 0) {
		$_SESSION['event'] = '<span class="red">Game over, you don\'t have more money!</span>';
		$played_games += 1;
		$defeats += 1;
		new_game($conn, $username, rand(5, 10), rand(1, 12));
	} elseif ($_SESSION['motels'] == 3) {
		$_SESSION['event'] = "<span class='green'>Congratulations, you bought all motels and own the village!</span> (".$_SESSION['money']." COINS";
		$played_games += 1;
		$victories += 1;
		new_game($conn, $username, rand(5, 10), rand(1, 12));
	} else if ($_SESSION['moves'] == 0) {
		$_SESSION['event'] = '<span class="red">Game over, you didn\'t have more moves!</span> ('.$_SESSION['money'].' COINS)';
		$played_games += 1;
		$defeats += 1;
		new_game($conn, $username, rand(5, 10), rand(1, 12));
	} elseif ($_SESSION['vso_field'] == 1) {
		$_SESSION['event'] = "<span class='green'>Congratulations, you won with the support of VSO!</span>";
		$played_games += 1;
		$victories += 1;
		new_game($conn, $username, rand(5, 10), rand(1, 12));
	}

	$update_query = "UPDATE users SET played_games = $played_games, victories = $victories, defeats = $defeats WHERE username = '$username'";
	$update_result = mysqli_query($conn, $update_query);
}
?>
<div class="nav">
	<div class="nav_game_name">iT-Village</div>
	<div class="user_to_game_name">| <?=$username?></div>

	<div class="nav_container">
		<form action="" method="post">
			<input class="btn btn_save" type="submit" name="save" value="Save">
			<?php
			if (isset($_POST['save'])) {
				$save_progress_query = "UPDATE users SET in_progress = 'YES', last_money = '{$_SESSION['money']}', remaining_moves = '{$_SESSION['moves']}', last_field = '{$_SESSION['field']}', motels = '{$_SESSION['motels']}' WHERE username = '$username'";
				$save_progress_result = mysqli_query($conn, $save_progress_query);

				$_SESSION['event'] = "<span class='green underline'>THE GAME IS SAVED SUCCESSFULLY!</span><br>(If you start a new game the progress of this one will be delete!)";
			}?>

			<input class="btn btn_quit" type="submit" name="quit" value="Quit">
			<?php
			if (isset($_POST['quit'])) {
				session_destroy();
				header("Location: index.php");
			}?>
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
		<div class="my_username"><?=$username?></div>
		<div class="my_victories"><?=$result_row['victories']?></div>
		<div class="my_total_games"><?=$result_row['played_games']?></div>
	</div>
	<form action="" method="post">
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
<?php include 'resources/includes/footer.php'; ?>