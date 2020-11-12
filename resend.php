<?php include("connect.php"); ?>
<html>
<head>
<style>
<?php include("style.css"); ?>
</style>
<title>Re-send Confirmation Email</title>
</head>
<body>
<?php include("header.php"); ?>
<h1>Re-send Confirmation Email</h1>
<?php
  if(!isset($_GET["id"])) echo "<h2>Invalid ID</h2>";
	else {
		$data = get_data("SELECT * FROM users WHERE id = '{$_GET['id']}'");
		if($data["error"]) echo "<h2>Error getting user</h2>";
		elseif(count($data["results"]) != 1) echo "<h2>Invalid ID</h2>";
		elseif($data["results"][0]["mailed"] == 1) echo "<h2>The email address has already been confirmed for this account.</h2>";
		else {
			$username = $data["results"][0]["username"];
			$friend = $data["results"][0]["fc"];
			$email = $data["results"][0]["email"];
			sendmail($email, $username, $friend, $data["results"][0]["id"]);
			echo "<h2>Re-sent email. Check your mailbox.</h2>";
		}
	}
?>
<?php include("footer.php"); ?>
</body>
</html>