<?php include("connect.php"); ?>
<html>
<head>
<style>
<?php include("style.css"); ?>
</style>
<title>Register</title>
</head>
<body>
<?php include("header.php"); ?>
<h1>Register</h1>
<?php
	if($_SERVER["REQUEST_METHOD"] === "POST") {
		if(!isset($_POST["username"]) || $_POST["username"] === "") echo "<h2>The username should not be empty. Please <a href='/register.php' class='redirect'>try again.</a></h2>";
		elseif(!isset($_POST["password"]) || !preg_match('/^\w{8,}/', $_POST["password"])) echo "<h2>The password should only consist of uppercase letters, lowercase letters, numbers and underscores and must be more than 7 character. Please <a href='/register.php' class='redirect'>try again.</a></h2>";
		elseif(!isset($_POST["email"]) || !preg_match('/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/', $_POST["email"])) echo "<h2>The email address is invalid. Please <a href='/register.php' class='redirect'>try again.</a></h2>";
		else {
			$username = $_POST["username"];
			$password = hash("sha256", $_POST["password"]);
			$password1 = hash("sha256", $_POST["password1"]);
			$friend = $_POST["friendcode"];
			$email = $_POST["email"];
			$code = uniqid("smm2db_", true);
			$data = get_data("SELECT id FROM users WHERE username = '".escape_string($username)."'");
			if(!preg_match('/^SW-[0-9]{4}-[0-9]{4}-[0-9]{4}/', $friend)) echo "<h2>The friend code provided is invalid. Please <a href='/register.php' class='redirect'>try again.</a></h2>";
			elseif($password !== $password1) echo "<h2>The two password didn't match. Please <a href='/register.php' class='redirect'>try again</a>.</h2>";
			elseif(count($data["results"]) > 0) echo "<h2>There is already an user named ".$username.". Please <a href='/register.php' class='redirect'>try again.</a></h2>";
			else {
				$result = get_data("INSERT INTO users VALUES('{$code}', '".escape_string($username)."', '".escape_string($friend)."', '".escape_string($password)."', '".escape_string($email)."', 0, 0)");
				if($result["error"]) echo "<h2>Failed to register your account. Please <a href='/register.php' class='redirect'>try again</a> later.</h2>";
				else {
					echo "<h2>Your account has been registered successfully. Please check your mailbox for a confirmation email and wait for the approval of your account. Click <a href='/' class='redirect'>here to go to the main page</a>.</h2>";
					sendmail($email, $username, $friend, $code);
				}
			}
		}
	} elseif(isset($_COOKIE["id"]) || isset($_COOKIE["username"])) {
		echo "<h2>You are already logged in.</h2>";
	} else {
?>
<form action="/register.php" method="post">
  <input type="text" name="username" placeholder="Username..."><br>
  <input type="text" name="friendcode" placeholder="Friend Code..."><br>
	<input type="email" name="email" placeholder="Email..."><br>
  <input type="password" name="password" placeholder="Password..."><br>
  <input type="password" name="password1" placeholder="Password Again..."><br>
  <input type="submit" value="Register"">
	<?php } ?>
</form>
<?php include("footer.php"); ?>
</body>
</html>