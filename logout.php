<?php include("connect.php"); ?>
<?php
$h2 = "<h2>Logged out successfully. Click <a href='/' class='redirect'>here to go back</a>.</h2>";
if(!isset($_COOKIE["id"]) || !isset($_COOKIE["username"])) $h2 = "<h2>You've logged out already.</h2>";
else {
	unset($_COOKIE["id"]);
	unset($_COOKIE["username"]);
  setcookie('id', null, -1, '/');
  setcookie('username', null, -1, '/');
}
?>
<html>
<head>
<style>
<?php include("style.css"); ?>
</style>
<title>Log Out</title>
</head>
<body>
<?php include("header.php"); ?>
<h1>Log Out</h1>
<?php
	echo $h2;
?>
<?php include("footer.php"); ?>
</body>
</html>