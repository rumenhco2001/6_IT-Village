<?php
$title = "iT-Village | Sign up";
include 'resources/includes/header.php';
session_start();
?>
<a class="btn btn-success btn_back" href="index.php">&#x261A;</a>
<a class="game_name" href="index.php">iT-Village</a>

<div class="log_panel">
	<div class="form_heading">Sign up</div>

	<form action="" method="post">
		<label for="username">Username</label>
		<input class="form-control" type="text" id="username" name="username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" placeholder="Username">

		<label for="password">Password</label>
		<input class="form-control" type="password" id="password" name="password" placeholder="Password">

		<?php
		if (isset($_POST['sign_up'])) {
			$username = $_POST['username'];
			$password = $_POST['password'];

			if (!empty($username) && !empty($password)) {
				$read_query = "SELECT username, password FROM users WHERE 1";
				$users_and_passwords = mysqli_query($conn, $read_query);
				
				$same_data = 0;
				$occupied_username = 0;
				$occupied_password = 0;
				
				while ($row = mysqli_fetch_assoc($users_and_passwords)) {
					if ($row['username'] == $username && $row['password'] == $password) {
						$same_data++;
					} else {
						if ($row['username'] == $username) {
							$occupied_username++;
						}
						if ($row['password'] == $password) {
							$occupied_password++;
						}
					}
				}

				if ($same_data != 0) {
					echo '<div class="error">'.'There is already such a user!'.'</div>';
				} elseif ($occupied_username != 0 && $occupied_password == 0) {
					echo '<div class="error">'.'This USERNAME already exists!'.'</div>';
				} elseif ($occupied_username == 0 && $occupied_password != 0) {
					echo '<div class="error">'.'This PASSWORD already exists!'.'</div>';
				} elseif (strlen($username) < 7) {
					echo '<div class="error">'.'The USERNAME can not be less than<br> 7 symbols!'.'</div>';
				} elseif (strlen($username) > 25) {
					echo '<div class="error">'.'The USERNAME can not be more than<br> 25 symbols!'.'</div>';
				} elseif (strlen($password) < 10) {
					echo '<div class="error">'.'The PASSWORD can not be less than<br> 10 symbols!'.'</div>';
				} elseif (strlen($password) > 25) {
					echo '<div class="error">'.'The PASSWORD can not be more than<br> 25 symbols!'.'</div>';
				} else {
					$insert_query = "INSERT INTO users(username, password) VALUES ('$username', '$password')";
					$insert_result = mysqli_query($conn, $insert_query);

					session_start();
					$_SESSION['username'] = $username;

					$_SESSION['money'] = 50;
					$_SESSION['field'] = rand(1, 12);
					$_SESSION['moves'] = rand(5, 10);
					$_SESSION['motels'] = 0;
					$_SESSION['vso_field'] = 0;

					$_SESSION['event'] = "<span style='font-size: 1.2vw;'><span class='green'>Welcome to iT-Village, $username!</span><br>&#8226; You always start the game with 50 coins and some random moves<br>&#8226; Press the button to the right to throw the dice and read the text here to understand what it is going on, you will manage!</span>";

					header('Location: it-village.php');
				}
			} else {
				echo '<div class="error">'.'Empty field(s)!'.'</div>';
			}
		}
		?>
		<input class="btn log_btn" type="submit" name="sign_up" value="Sign up">
		<div class="under_log_btn">The registration is free and will always be!</div>
	</form>
</div>
<?php include 'resources/includes/footer.php'; ?>
