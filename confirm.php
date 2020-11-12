<?php include("connect.php"); ?>
<html>
<head>
<style>
<?php include("style.css"); ?>
</style>
<title>Email Confirmation</title>
</head>
<body>
<?php include("header.php"); ?>
<h1>Email Confirmation</h1>
<?php
if(!isset($_GET["id"])) echo "<h2>Error confirming email address</h2>";
else {
	$data = get_data("SELECT id FROM users WHERE id = '{$_GET['id']}'");
	if($data["error"]) echo "<h2>Error confirming email address</h2>";
	elseif(count($data["results"]) != 1) echo "<h2>Invalid confirmation link</h2>";
	else {
		$data = get_data("UPDATE users SET mailed = 1 WHERE id = '{$_GET['id']}'");
		if($data["error"]) echo "<h2>Error changing confimration state</h2>";
		else echo "<h2>The email address has been confirmed! Your account will now undergo approval.</h2>";
	}
}
?>
<?php include("footer.php"); ?>
</body>
</html>