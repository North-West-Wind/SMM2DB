<?php
	if(!isset($_COOKIE["welcomed"])) setcookie("welcomed", true, time() + (10 * 365 * 24 * 60 * 60));
?>
<html>
<head>
<style>
<?php include("style.css"); ?>
</style>
<title>Welcome to SMM2DB!</title>
</head>
<body>
<?php include("header.php"); ?>
<h1>Welcome to the Super Mario Maker 2 Database!</h1>
<h3>This page will appear for once only. It won't show up everytime you enter.</h3>
<p>This is a fan-made website that allows Super Mario Maker 2 players to upload details about their courses.</p>
<p>As everyone knows that the searching system in both Super Mario Maker and Super Mario Maker 2 sucks, this website allows you to search courses by keywords, makers, themes and more! How convenient!</p>
<p>It is stupidly annoying to get a Nintendo API so this website doesn't actually fetch courses from the official database. Therefore, please upload your courses' details to this database.</p>
<p>As a reminder, please avoid making mistakes in the details.</p>
<p>This is not <a href="https://smmdb.net" target="smmdb" style="color: black;">SMMDB</a>. We don't provide course downloads.</p>
<h3>If you want to comeback to this page in the future, you can either memorize this link or delete your cookies.</h3>
<h2>Click the button below and you will be taken to the main page. Have fun!</h2>
<a href="/" class="button">Start</a>
<?php include("footer.php"); ?>
</body>
</html>