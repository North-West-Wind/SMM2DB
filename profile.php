<?php include("connect.php"); ?>
<html>
    <head>
        <style>
            <?php include("style.css"); ?>
        </style>
        <title>Profile</title>
    </head>
    <body>
        <?php include("header.php"); ?>
        <h1>Profile</h1>
        <?php
            if(!isset($_GET["id"])) echo "<h2>Invalid ID</h2>";
            else {
							$data = get_data("SELECT * FROM users WHERE `id` = '".$_GET["id"]."'");
              if(count($data["results"]) < 1) echo "<h2>Invalid ID</h2>";
              elseif($data["error"]) echo "<h2>Error fetching the user profile</h2>";
							else foreach($data["results"] as $row) {
                echo "<table class='profile'>
									<tr class='profile'><th class='profile'>Username</th></tr>
									<tr class='profile'><td class='profile'>{$row['username']}</td></tr>
									<tr class='profile'><th class='profile'>Friend Code</th></tr>
									<tr class='profile'><td class='profile'>{$row['fc']}</td></tr>
                  </table>";
								echo "<h1>Uploaded Courses</h1>";
								$courses = get_data("SELECT id, name, maker, code FROM courses WHERE uploader = '{$row['id']}'");
								if($courses["error"]) echo "<h2>Error fetching courses</h2>";
								else foreach($courses["results"] as $row) {
      						echo '<table class="list"><tr class="top"><th class="namecolumn top">Name</th><th class="makercolumn top">Maker</th><th class="codecolumn top">Code</th></tr><tr class="courses" onclick="window.location.href = \'course.php?id='.$row["id"].'\';"><td class="namecolumn courses">'.$row["name"].'</td><td class="makercolumn courses">'.$row["maker"].'</td><td class="codecolumn courses">'.$row["code"].'</td></tr></table>';
              	}
							}
            }
        ?>
			<?php include("footer.php"); ?>
    </body>
</html>