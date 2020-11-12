<?php include("connect.php"); ?>
<html>
    <head>
        <style>
            <?php include("style.css"); ?>
        </style>
        <title>Course Detail</title>
    </head>
    <body>
        <?php include("header.php"); ?>
        <h1>Course Detail</h1>
        <?php
            if(!isset($_GET["id"])) echo "<h2>Invalid ID</h2>";
            else {
							$data = get_data("SELECT `courses`.*, `users`.`username` FROM `courses` LEFT JOIN `users` ON `courses`.`uploader` = `users`.`id` WHERE `courses`.`id` = '".$_GET["id"]."'");
              if(count($data["results"]) != 1) echo "<h2>Invalid ID</h2>";
              elseif($data["error"]) echo "<h2>Error fetching the course details</h2>";
							else foreach($data["results"] as $row) {
								if($row["approved"] != 1) echo "<h3>This level has not been approved yet.</h3>";
                echo '<table class="details">
                  <tr class="texts"><th class="leftcolumn">Code</th><th class="rightcolumn">Name</th></tr>
                  <tr class="details"><td class="leftcolumn">'. $row["code"] .'</td><td class="rightcolumn">'. $row["name"] .'</td></tr>
                  <tr class="texts"><th class="leftcolumn toprofile" onclick="window.location.href = \'/profile.php?id='.$row["uploader"].'\'">Uploader</th><th class="rightcolumn">Maker</th></tr>
                  <tr class="details"><td class="leftcolumn toprofile" onclick="window.location.href = \'/profile.php?id='.$row["uploader"].'\'">'. $row["username"] .'</td><td class="rightcolumn">'. $row["maker"] .'</td></tr>
                  <tr class="texts"><th class="leftcolumn">Style</th><th class="rightcolumn">Themes</th></tr>
                  <tr class="details"><td class="leftcolumn">'. $styles[$row["style"]] .'</td><td class="rightcolumn">'. ($row["tag_1"] === null ? "None" : $tags[$row["tag_1"]] .", ". ($row["tag_2"] === null ? "None" : $tags[$row["tag_2"]])) .'</td></tr>
                  <tr class="texts"><th class="leftcolumn">Uploaded Date</th><th class="rightcolumn">Published Date</th></tr>
                  <tr class="details"><td class="leftcolumn">'. date("d/m/Y H:i:s", strtotime($row["uploaded"])) .'</td><td class="rightcolumn">'. date("d/m/Y H:i:s", strtotime($row["published"])) .'</td></tr>
                  <tr class="texts"><th class="leftcolumn">Time Limit</th><th class="rightcolumn">Description</th></tr>
                  <tr class="details"><td class="leftcolumn">'. $row["time"] .'</td><td class="rightcolumn description" rowspan="7"><span class="description">'. ($row["description"] === null ? "*No description*" : $row["description"]) .'</span></td></tr>
                  <tr class="texts"><th class="leftcolumn">Theme (Main, Sub)</th></tr>
                  <tr class="details"><td class="leftcolumn">'. $themes[$row["theme"]] . ", " . ($row["sub_theme"] === null ? "None" : $themes[$row["sub_theme"]]) .'</td></tr>
                  <tr class="texts"><th class="leftcolumn">Day/Night (Main, Sub)</th></tr>
                  <tr class="details"><td class="leftcolumn">'. ($row["day"] == 0 ? "Day" : "Night") . ", " .($row["sub_day"] === null ? "None" : ($row["sub_day"] == 0 ? "Day" : "Night")) .'</td></tr>
                  <tr class="texts"><th class="leftcolumn">Direction (Main, Sub)</th></tr>
                  <tr class="details"><td class="leftcolumn">'. ($row["direction"] == 0 ? "Horizontal" : "Vertical") . ", " .($row["sub_direction"] === null ? "None" : ($row["sub_direction"] == 0 ? "Horizontal" : "Vertical")) .'</td></tr>
                  </table>';
									if(isset($_COOKIE["id"]) && $_COOKIE["id"] == $row["uploader"]) {
										echo "<a href='/edit.php?id={$row['id']}' class='button'>Edit</a><a href='/delete.php?id={$row['id']}' class='button'>Delete</a>";
									}
              }
            }
        ?>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
				<script>
					$(".toprofile").on("mouseover", function() {
						$(".toprofile").addClass("hover");
					});
					$(".toprofile").on("mouseleave", function() {
						$(".toprofile").removeClass("hover");
					});
				</script>
			<?php include("footer.php"); ?>
    </body>
</html>