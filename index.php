<?php
$title = "iT-Village | Log in";
include 'resources/includes/header.php';
?>
<a class="game_name" href="index.php">iT-Village</a> <!-- logo -->
<div class="log_panel">
	<div class="form_heading">Log in</div>

	<form action="" method="post">
		<label class="form_label" for="username">Username</label>
		<input class="form-control" type="text" id="username" name="username" value="<?php if (isset($_POST['username'])) { echo $_POST['username']; }?>" placeholder="Username">

		<label class="form_label" for="password">Password</label>
		<input class="form-control" type="password" id="password" name="password" placeholder="Password">

		<?php
		if (isset($_POST['log_in'])) {
			$username = $_POST['username'];
			$password = $_POST['password'];

			if (!empty($username) && !empty($password)) {
				$read_query = "SELECT username, password FROM users WHERE 1";
				$users_and_passwords = mysqli_query($conn, $read_query);

				$correct_data = 0;
				$correct_username = 0;

				while ($row = mysqli_fetch_assoc($users_and_passwords)) {
					if ($row['username'] == $username && $row['password'] == $password) {
						$correct_data++;
					} elseif ($row['username'] == $username && $row['password'] != $password) {
						$correct_username++;
					}
				}

				if ($correct_data == 1) {
					session_start();
					$_SESSION['username'] = $username;

					$read_query = "SELECT in_progress, played_games FROM users WHERE username = '$username'";
					$result_query = mysqli_query($conn, $read_query);
					$result_row = mysqli_fetch_assoc($result_query);

					$in_progress = $result_row['in_progress'];
					$played_games = $result_row['played_games'];

					if ($in_progress == "YES") {
						header('Location: do_you_continue.php');
					} elseif ($in_progress == "NO") {
						$field = rand(1, 12);
						$moves = rand(5, 10);
						$_SESSION['money'] = 50;
						$_SESSION['field'] = $field;
						$_SESSION['moves'] = $moves;
						$_SESSION['motels'] = 0;
						$_SESSION['vso_field'] = 0;

						if ($played_games > 0) {
							$_SESSION['event'] = "<span style='font-size: 1.55vw;'>Welcome again, <span class='green'>$username</span>!<br>"."This time you have <span class='green'>$moves moves</span> and the initial field is <span class='green'>$field</span>.<br><br>".'Let\'s play!</span>';
						} else {
							$_SESSION['event'] = "<span style='font-size: 1.2vw;'><span class='green'>Welcome to iT-Village, $username!</span><br>&#8226; You always start the game with 50 coins and some random moves<br>&#8226; Press the button to the right to throw the dice and read the text here to understand what it is going on, you will manage!</span>";
						}
						header('Location: it-village.php');
					}				
				} else {
					if ($correct_username == 1) {
						echo '<div class="error_text">'.'Invalid PASSWORD!'.'</div>';
					} else {
						echo '<div class="error_text">'.'Invalid DATA!'.'</div>';
					}
				}
			} else {
				echo '<div class="error_text">'.'Empty field(s)!'.'</div>';
			}
		}
		?>
		<input class="btn btn_log" type="submit" name="log_in" value="Log in">
		<span class="text_under_log_btn">
			<span>You have not account, </span><a class="btn btn-link sign_up_link" href="sign_up.php">Sign up now</a>
		</span>	
	</form>
</div>
<?php include 'resources/includes/footer.php'; ?>