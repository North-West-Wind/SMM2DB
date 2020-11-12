<?php include("connect.php"); ?>
<html>
<head>
<style>
<?php include("style.css"); ?>
</style>
<?php
	$page = isset($_GET["page"]) && intval($_GET["page"] > 0) ? $_GET["page"] : 1;
	echo "<title>SMM2DB Courses (Page {$page})</title>";
?>
</head>
<body>
<?php include("header.php"); ?>
<h1>Course List</h1>
<table class="list">
    <tr class="top"><th class="namecolumn top">Name</th><th class="makercolumn top">Maker</th><th class="codecolumn top">Code</th></tr>
    <?php
			$offset = ($page - 1) * 50;
			$data = get_data("SELECT id, name, maker, code FROM courses WHERE approved = 1 ORDER BY uploaded DESC LIMIT {$offset}, 50");
      foreach($data["results"] as $row) {
      	echo '<tr class="courses" onclick="window.location.href = \'course.php?id='.$row["id"].'\';"><td class="namecolumn courses">'.$row["name"].'</td><td class="makercolumn courses">'.$row["maker"].'</td><td class="codecolumn courses">'.$row["code"].'</td></tr>';
      }
    ?>
</table>
<div class="pages">
<?php
	$all = get_data("SELECT COUNT(*) AS total FROM courses");
	if($all["error"]) echo "<a href='/courses.php?page=1' class='redirect'>1</a>";
	else {
		$pages = ceil($all["results"][0]["total"]/50);
		for($i = 1; $i <= $pages; $i++) echo "<a href='/courses.php?page={$i}' class='redirect'>{$i}</a>";
	}
?>
</div>
<?php include("footer.php"); ?>
</body>
</html>