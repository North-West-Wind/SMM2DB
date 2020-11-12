<?php
	if(!isset($_COOKIE["welcomed"])) header("Location: /welcome.php");
	include("connect.php");
?>
<html>
<head>
<style>
<?php include("style.css"); ?>
</style>
<title>SMM2DB</title>
</head>
<body>
<?php include("header.php"); ?>
<h1>Latest</h1>
<table class="list" id="courses">
    <tr class="top"><th class="namecolumn top">Name</th><th class="makercolumn top">Maker</th><th class="codecolumn top">Code</th></tr>
    <?php
			$data = get_data("SELECT id, name, maker, code FROM courses WHERE approved = 1 ORDER BY uploaded DESC LIMIT 30");
      foreach($data["results"] as $row) {
      	echo '<tr class="courses" onclick="window.location.href = \'/course.php?id='.$row["id"].'\';"><td class="namecolumn courses">'.$row["name"].'</td><td class="makercolumn courses">'.$row["maker"].'</td><td class="codecolumn courses">'.$row["code"].'</td></tr>';
      }
    ?>
</table>
<?php include("footer.php"); ?>
</body>
</html>