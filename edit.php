<?php include("connect.php"); ?>
<html>
    <head>
        <style>
            <?php include("style.css"); ?>
        </style>
        <title>Edit Course</title>
    </head>
    <body>
        <?php include("header.php"); ?>
        <h1>Edit Course</h1>
        <?php
						if($_SERVER["REQUEST_METHOD"] === "POST") {
							if(!isset($_COOKIE["id"])) echo "<h2>You cannot edit this course</h2>";
							elseif(!isset($_POST["id"]) || !preg_match('/^smm2db_\w{14}\.\w{8}/', $_POST["id"])) echo "<h2>Invalid ID</h2>";
							elseif(!isset($_POST["name"]) || $_POST["name"] == "") echo "<h2>Please provide the name and <a href='/edit.php?id=\"{$_POST['id']}\"' class='redirect'>try again</a>.</h2>";
							elseif(!isset($_POST["code"]) || !preg_match('/^[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}/', $_POST["code"])) echo "<h2>Please provide the level code and <a href='/edit.php?id=\"{$_POST['id']}\"' class='redirect'>try again</a>.</h2>";
							elseif(!isset($_POST["published"]) || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}/', $_POST["published"])) echo "<h2>Please provide the published date and <a href='/edit.php?id=\"{$_POST['id']}\"' class='redirect'>try again</a>.</h2>";
							elseif(!isset($_POST["style"]) || $_POST["style"] == "none" || $_POST["style"] == "") echo "<h2>Please provide the style and <a href='/edit.php?id=\"{$_POST['id']}\"' class='redirect'>try again</a>.</h2>";
							elseif(!isset($_POST["main_theme"]) || $_POST["main_theme"] == "none") echo "<h2>Please provide the theme of the main area and <a href='/edit.php?id=\"{$_POST['id']}\"' class='redirect'>try again</a>.</h2>";
							elseif(!isset($_POST["main_day"]) || $_POST["main_day"] == "none") echo "<h2>Please provide the time of the main area and <a href='/edit.php?id=\"{$_POST['id']}\"' class='redirect'>try again</a>.</h2>";
							elseif(!isset($_POST["main_dir"]) || $_POST["main_dir"] == "none") echo "<h2>Please provide the direction of the main area and <a href='/edit.php?id=\"{$_POST['id']}\"' class='redirect'>try again</a>.</h2>";
							elseif(!isset($_POST["time"]) || $_POST["time"] == "") echo "<h2>Please provide the time limit and <a href='/edit.php?id=\"{$_POST['id']}\"' class='redirect'>try again</a>.</h2>";
							elseif(intval($_POST["time"]) < 1 || intval($_POST["time"]) > 500) echo "<h2>The time limit is invalid. Please <a href='/edit.php?id=\"{$_POST['id']}\"' class='redirect'>try again</a>.</h2>";
							elseif(isset($_POST["sub_theme"]) && $_POST["sub_theme"] != "none" && (!isset($_POST["sub_day"]) || $_POST["sub_day"] == "none" || !isset($_POST["sub_dir"]) || $_POST["sub_dir"] == "none")) echo "<h2>Please provide all the details about the sub area and <a href='/edit.php?id=\"{$_POST['id']}\"' class='redirect'>try again</a>.</h2>";
							else {
								$data = get_data("SELECT uploader FROM courses WHERE id = '{$_POST['id']}'");
             	  if(count($data["results"]) < 1) echo "<h2>Invalid ID</h2>";
								elseif($data["error"]) echo "<h2>Error editting course detail</h2>";
								else {
									$result = $data["results"][0];
									if($_COOKIE["id"] != $result["uploader"]) echo "<h2>You cannot edit this course</h2>";
									else {
										$name = $_POST["name"];
										$code = $_POST["code"];
										$maker = !isset($_POST["maker"]) || $_POST["maker"] === "" ? $_COOKIE["username"] : $_POST["maker"];
										$time = $_POST["time"];
										$date = date("Y-m-d H:i:s", strtotime($_POST["published"]));
										$description = isset($_POST["description"]) && $_POST["description"] !== "" ? $_POST["description"] : "NULL";
										$style = array_search($_POST["style"], array_map('strtolower', $styles));
										$maintheme = array_search($_POST["main_theme"], array_map('strtolower', $themes));
										$subtheme = isset($_POST["sub_theme"]) && $_POST["sub_theme"] != "none" ? array_search($_POST["sub_theme"], array_map('strtolower', $themes)) : "NULL";
										$mainday = $_POST["main_day"] != "day" ? 1 : 0;
										$subday = isset($_POST["sub_day"]) && $_POST["sub_day"] != "none" ? ($_POST["sub_day"] != "day" ? 1 : 0) : "NULL";
										$maindir = $_POST["main_dir"] != "hori" ? 1 : 0;
										$subdir = isset($_POST["sub_dir"]) && $_POST["sub_dir"] != "none" ? ($_POST["sub_dir"] != "hori" ? 1 : 0) : "NULL";
										$tag1 = $_POST["tag_1"] != "none" ? array_search($_POST["tag_1"], array_map('strtolower', $tags)) : "NULL";
										$tag2 = $_POST["tag_2"] != "none" ? array_search($_POST["tag_2"], array_map('strtolower', $tags)) : "NULL";
										$id = $_POST["id"];
										$name = escape_string($name);
										$maker = escape_string($maker);
										$code = escape_string($code);
										$description = $description == "NULL" ? "NULL" : "'".escape_string($description)."'";
										$data = get_data("UPDATE courses SET name = '{$name}', code = '{$code}', maker = '{$maker}', published = '{$date}', description = {$description}, style = {$style}, theme = {$maintheme}, sub_theme = {$subtheme}, day = {$mainday}, sub_day = {$subday}, direction = {$maindir}, sub_direction = {$subdir}, tag_1 = {$tag1}, tag_2 = {$tag2} WHERE id = '{$id}'");
										if($data["error"]) echo "<h2>Error saving the editted course details</h2>";
										else echo "<h2>The course has been edited! <a href='/course.php?id={$id}' class='redirect'>Click here to return to the course page</a>.</h2>";
									}
								}
							}
						} elseif(!isset($_GET["id"])) echo "<h2>Invalid ID</h2>";
            else {
							$data = get_data("SELECT `courses`.*, `users`.`username` FROM `courses` LEFT JOIN `users` ON `courses`.`uploader` = `users`.`id` WHERE `courses`.`id` = '".$_GET["id"]."'");
              if(count($data["results"]) < 1) echo "<h2>Invalid ID</h2>";
              elseif($data["error"]) echo "<h2>Error fetching the course details</h2>";
							else foreach($data["results"] as $row) {
								if(!isset($_COOKIE["id"]) || $_COOKIE["id"] != $row["uploader"]) echo "<h2>You cannot edit this course</h2>";
								else {
									echo "<form action='/edit.php' method='post'>
									<input type='hidden' name='id' value='{$_GET['id']}'>
  								<input type='text' name='name' placeholder='Name...' value='{$row['name']}'><br>
  								<input type='text' name='maker' placeholder='Maker (Leave blank for yourself)...' value='{$row['maker']}'><br>
  								<input type='text' name='code' placeholder='Code...' value='{$row['code']}'><br>
  								<input type='text' name='time' placeholder='Time Limit...' value='{$row['time']}'><br>
  								<input type='text' name='published' placeholder='Published Date...' onfocus='(this.type=\"datetime-local\")' onfocusout='(this.type=\"text\")' value='{$row['published']}'><br>
									<select name='style'>";
										foreach($styles as $style) {
											if($style === $styles[$row["style"]]) echo '<option value="'.strtolower($style).'" selected>'.$style.'</option>';
											else echo '<option value="'.strtolower($style).'">'.$style.'</option>';
										}
									echo "</select><br>
										<select name='main_theme'>";
									foreach($themes as $theme) {
										if($theme === $themes[$row["theme"]]) echo '<option value="'.strtolower($theme).'" selected>'.$theme.'</option>';
										else echo '<option value="'.strtolower($theme).'">'.$theme.'</option>';
									}
									echo "</select>
										<select name='sub_theme' onchange='subChanged()'>";
									if($row["sub_theme"] === null) echo "<option value='none' selected>None</option>";
									else echo "<option value='none'>None</option>";
									foreach($themes as $theme) {
										if($theme === $themes[$row["sub_theme"]]) echo '<option value="'.strtolower($theme).'" selected>'.$theme.'</option>';
										else echo '<option value="'.strtolower($theme).'">'.$theme.'</option>';
									}
									echo "</select><br>
										<select name='main_day'>";
									if($row["day"] == 0) echo "<option value='day' selected>Day</option>
										<option value='night'>Night</option>";
									else echo "<option value='day'>Day</option>
										<option value='night'selected>Night</option>";
									echo "</select>";
									if($row["sub_theme"] === null) echo	"<select name='sub_day' disabled>";
									else echo "<select name='sub_day'>";
									if($row["sub_day"] === null) echo "<option disabled selected value='none'>Select Sub Area Day/Night...</option>
										<option value='day'>Day</option>
										<option value='night'>Night</option>";
									elseif($row["sub_day"] == 0) echo "<option value='day' selected>Day</option>
										<option value='night'>Night</option>";
									else echo "<option value='day'>Day</option>
										<option value='night'selected>Night</option>";
									echo "</select><br>
										<select name='main_dir'>";
									if($row["direction"] == 0) echo "<option value='hori' selected>Horizontal</option>
										<option value='vert'>Vertical</option>";
									else echo "<option value='hori'>Horizontal</option>
										<option value='vert' selected>Vertical</option>";
									echo "</select>";
									if($row["sub_theme"] === null) echo	"<select name='sub_dir' disabled>";
									else echo "<select name='sub_dir'>";
									if($row["sub_direction"] === null) echo "<option disabled selected= value='none'>Select Sub Area Direction...</option>
										<option value='hori'>Horizontal</option>
										<option value='vert'>Vertical</option>";
									elseif($row["sub_direction"] == 0) echo "<option value='hori' selected>Horizontal</option>
										<option value='vert'>Vertical</option>";
									else echo "<option value='hori'>Horizontal</option>
										<option value='vert' selected>Vertical</option>";
									echo "</select><br>
										<select name='tag_1' onchange='tagChanged()'>";
									if($row["tag_1"] === null) $row["tag_1"] = 0;
									foreach($tags as $tag) {
										if($tag === $tags[$row["tag_1"]]) echo '<option value="'.strtolower($tag).'" selected>'.$tag.'</option>';
										else echo '<option value="'.strtolower($tag).'">'.$tag.'</option>';
									}
									echo "</select><select name='tag_2'>";
									if($row["tag_2"] === null) $row["tag_2"] = 0;
									foreach($tags as $tag) {
										if($tag === $tags[$row["tag_2"]]) echo '<option value="'.strtolower($tag).'" selected>'.$tag.'</option>';
										else echo '<option value="'.strtolower($tag).'">'.$tag.'</option>';
									}
									echo "</select><br>";
									if($row["description"] === null) echo "<textarea placeholder='Description...' name='description'></textarea><br>";
  								else echo "<textarea placeholder='Description...' name='description' value='{$row['description']}'></textarea><br>";
									echo "<input type='submit' value='Save'>
										<script>
										function subChanged() {
  										var x = document.getElementsByName('sub_theme')[0].value;
											document.getElementsByName('sub_day')[0].disabled = x == 'none';
											document.getElementsByName('sub_dir')[0].disabled = x == 'none';
										}
										function tagChanged() {
  										var x = document.getElementsByName('tag_1')[0].value;
											document.getElementsByName('tag_2')[0].disabled = x == 'none';
										}
										$('select').on('change', function(ev) {
											if(ev.target.value == 'none')	$(this).removeClass();
											else $(this).attr('class', '').addClass('notnone');
										});
										</script>
										</form>";
								}
              }
            }
        ?>
			<?php include("footer.php"); ?>
    </body>
</html>