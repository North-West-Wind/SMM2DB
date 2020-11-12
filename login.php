<?php include("connect.php"); ?>
<?php
$h2 = "";
if($_SERVER["REQUEST_METHOD"] === "POST") {
	if(!isset($_POST["username"]) || $_POST["username"] === "") $h2 = "<h2>You didn't provide the username. Please <a href='/login.php' class='redirect'>try again.</a></h2>";
	elseif(!isset($_POST["password"]) || $_POST["password"] === "") $h2 = "<h2>You didn't provide the password. Please <a href='/login.php' class='redirect'>try again.</a></h2>";
	else {
		$username = $_POST["username"];
		$password = hash("sha256", $_POST["password"]);
		$data = get_data("SELECT * FROM users WHERE username = '".escape_string($username)."' AND pass = '".escape_string($password)."'");
		if(count($data["results"]) != 1) $h2 = "<h2>Failed to log in. Please <a href='/login.php' class='redirect'>try again.</a></h2>";
		elseif($data["results"][0]["mailed"] == 0) {
			$h2 = "<h2>You didn't confirm your email. Please check your mailbox and <a href='/login.php' class='redirect'>try again.</a></h2><h2><a href='/resend.php?id={$data['results'][0]['id']}' class='redirect'>Re-send confirmation email</a></h2>";
		} elseif($data["results"][0]["approved"] == 0) {
			$h2 = "<h2>Your account is being approved. Please head back later.</h2>";
		} else {
			$h2 = "<h2>Logged in successfully. Click <a href='/' class='redirect'>here to go to the main page</a>.</h2>";
			setcookie("username", $username, time() + (10 * 365 * 24 * 60 * 60));
			setcookie("id", $data["results"][0]["id"], time() + (10 * 365 * 24 * 60 * 60));
		}
	}
}
?>
<html>
<head>
<style>
<?php include("style.css"); ?>
</style>
<title>Log In</title>
</head>
<body>
<?php include("header.php"); ?>
<h1>Log In</h1>
<?php
	if($_SERVER["REQUEST_METHOD"] === "POST") {
		echo $h2;
	} elseif(isset($_COOKIE["id"]) || isset($_COOKIE["username"])) {
		echo "<h2>You are already logged in.</h2>";
	} else {
?>
<form action="/login.php" method="post">
  <input type="text" name="username" placeholder="Username..."><br>
  <input type="password" name="password" placeholder="Password..."><br>
  <input type="submit" value="Login"">
	<?php } ?>
</form>
<?php include("footer.php"); ?>
</body>
</html>