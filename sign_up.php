<?php
$title = "iT-Village | Sign up";
include 'resources/includes/header.php';
?>
<a class="btn btn_back" href="index.php">&#x2190;</a>
<a class="game_name" href="index.php">iT-Village</a>

<div class="log_panel">
	<div class="form_heading">Sign up</div>
	<img class="avatar_img" src="resources/images/avatar.png">

	<form action="" method="post">
		<label style="margin-top: 0;" class="form_label" for="username">Username</label>
		<input class="form-control" type="text" id="username" name="username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" placeholder="Username">

		<label class="form_label" for="password">Password</label>
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
				
				while ($row = mysqli_fetch_assoc($users_and_passwords)) { //проверява дали потр.име и паролата са заети в базата данни
					if ($row['username'] == $username && $row['password'] == $password) { //ако са заети и потр.име, и паролата
						$same_data++; // увеличава числото
					} else {
						if ($row['username'] == $username) { //ако е заето само потр. име
							$occupied_username++; //увеличава стойността само на потр. име
						} elseif ($row['password'] == $password) {
							$occupied_password++;
						}
					}
				}
					//показва грешките:
				if ($same_data != 0) { //ако са заети и потр. име, и паролата
					echo '<div class="error_text">'.'There is already such a user!'.'</div>';
				} elseif ($occupied_username != 0 && $occupied_password == 0) { //ако е заето само потр.име
					echo '<div class="error_text">'.'This USERNAME already exists!'.'</div>';
				} elseif ($occupied_username == 0 && $occupied_password != 0) { //ако е заета само паролата
					echo '<div class="error_text">'.'This PASSWORD already exists!'.'</div>';
				} elseif (strlen($username) < 7) { //ако потр. име е по-кратка от 7 символа
					echo '<div class="error_text">'.'The USERNAME can not be less<br>than 7 symbols!'.'</div>';
				} elseif (strlen($username) > 20) { //ако потр. име е по-дълго от 20 символа
					echo '<div class="error_text">'.'The USERNAME can not be more<br>than 20 symbols!'.'</div>';
				} elseif (strlen($password) < 7) { //ако паролата е по-кратка от 7 символа
					echo '<div class="error_text">'.'The PASSWORD can not be less<br>than 7 symbols!'.'</div>';
				} elseif (strlen($password) > 20) { //ако паролата е по-дълка от 20 символа
					echo '<div class="error_text">'.'The PASSWORD can not be more<br>than 20 symbols!'.'</div>';
				} else {
					$insert_query = "INSERT INTO users(username, password) VALUES ('$username', '$password')"; //ако няма вече съществуващ потребител, то въведените данни се записват в базата данни
					$insert_result = mysqli_query($conn, $insert_query);

					session_start();
					$_SESSION['username'] = $username;

					$_SESSION['money'] = 50;
					$_SESSION['field'] = rand(1, 12);
					$moves = rand(5, 10);
					$_SESSION['moves'] = $moves;
					$_SESSION['motels'] = 0;
					$_SESSION['vso_field'] = 0;

					$_SESSION['event'] = "<span style='font-size: 1.2vw;'><span class='green'>Welcome to iT-Village, $username!</span><br>&#8226; You always start the game with 50 coins and some random moves<br>&#8226; Press the button to the right to throw the dice and read the text here to understand what it is going on, you will manage!</span>";

					header('Location: it-village.php');
				}
			} else {
				echo '<div class="error_text">'.'Empty field(s)!'.'</div>';
			}
		}
		?>
		<input class="btn btn_log" type="submit" name="sign_up" value="Sign up">
		<div class="text_under_log_btn">The registration is free and will always be!</div>
	</form>
</div>
<?php include 'resources/includes/footer.php'; ?>