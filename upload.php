<?php include("connect.php"); ?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>
<?php include("style.css"); ?>
</style>
<title>Upload Course</title>
</head>
<body>
<?php include("header.php"); ?>
<h1>Upload Course</h1>
<?php
	if($_SERVER["REQUEST_METHOD"] === "POST") {
		if(!isset($_POST["name"]) || $_POST["name"] == "") echo "<h2>Please provide the name and <a href='/upload.php' class='redirect'>try again</a>.</h2>";
		elseif(!isset($_POST["code"]) || !preg_match('/^[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}/', $_POST["code"])) echo "<h2>Please provide the level code and <a href='/upload.php' class='redirect'>try again</a>.</h2>";
		elseif(!isset($_POST["published"]) || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}/', $_POST["published"])) echo "<h2>Please provide the published date and <a href='/upload.php' class='redirect'>try again</a>.</h2>";
		elseif(!isset($_POST["style"]) || $_POST["style"] == "none") echo "<h2>Please provide the style and <a href='/upload.php' class='redirect'>try again</a>.</h2>";
		elseif(!isset($_POST["main_theme"]) || $_POST["main_theme"] == "none") echo "<h2>Please provide the theme of the main area and <a href='/upload.php' class='redirect'>try again</a>.</h2>";
		elseif(!isset($_POST["main_day"]) || $_POST["main_day"] == "none") echo "<h2>Please provide the time of the main area and <a href='/upload.php' class='redirect'>try again</a>.</h2>";
		elseif(!isset($_POST["main_dir"]) || $_POST["main_dir"] == "none") echo "<h2>Please provide the direction of the main area and <a href='/upload.php' class='redirect'>try again</a>.</h2>";
		elseif(!isset($_POST["time"]) || $_POST["time"] == "") echo "<h2>Please provide the time limit and <a href='/upload.php' class='redirect'>try again</a>.</h2>";
		elseif(intval($_POST["time"]) < 1 || intval($_POST["time"]) > 500) echo "<h2>The time limit is invalid. Please <a href='/upload.php' class='redirect'>try again</a>.</h2>";
		elseif(isset($_POST["sub_theme"]) && $_POST["sub_theme"] != "none" && (!isset($_POST["sub_day"]) || $_POST["sub_day"] == "none" || !isset($_POST["sub_dir"]) || $_POST["sub_dir"] == "none")) echo "<h2>Please provide all the details about the sub area and <a href='/upload.php' class='redirect'>try again</a>.</h2>";
		else {
			$name = $_POST["name"];
			$code = $_POST["code"];
			$maker = !isset($_POST["maker"]) || $_POST["maker"] == "" ? $_COOKIE["username"] : $_POST["maker"];
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
			$tag2 = isset($_POST["tag_2"]) && $_POST["tag_2"] != "none" ? array_search($_POST["tag_2"], array_map('strtolower', $tags)) : "NULL";
			$data = get_data("SELECT * FROM courses WHERE code = '".$code."'");
			if(count($data["results"]) > 0) echo "<h2>This course has already been uploaded by someone.</h2>";
			else {
				$id = uniqid('smm2db_', true);
				$name = escape_string($name);
				$maker = escape_string($maker);
				$code = escape_string($code);
				$description = $description == "NULL" ? "NULL" : "'".escape_string($description)."'";
				$result = get_data("INSERT INTO courses VALUES('{$id}', '{$name}', '{$maker}', '{$code}', '{$_COOKIE['id']}', NOW(), '{$date}', {$style}, {$description}, {$mainday}, {$maintheme}, {$maindir}, {$subday}, {$subtheme}, {$subdir}, {$tag1}, {$tag2}, {$time}, 0)");
				if($result["error"]) echo "<h2>Failed to upload course. Please <a href='/upload.php' class='redirect'>try again</a> later.</h2>";
				else echo "<h2>Course uploaded successfully.</h2>";
			}
		}
	} elseif(!isset($_COOKIE["id"]) || !isset($_COOKIE["username"])) {
		echo "<h2>Please <a class='redirect' href='/login.php'>log in</a> in order to upload courses.</h2>";
	} else {
?>
<form action="/upload.php" method="post">
  <input type="text" name="name" placeholder="Name..."><br>
  <input type="text" name="maker" placeholder="Maker (Leave blank for yourself)..."><br>
  <input type="text" name="code" placeholder="Code..."><br>
  <input type="text" name="time" placeholder="Time Limit..."><br>
  <input type="text" name="published" placeholder="Published Date..." onfocus="(this.type='datetime-local')" onfocusout="(this.type='text')"><br>
	<select name="style">
		<option disabled selected="selected" value="none">Select Style...</option>
		<?php
			foreach($styles as $style) {
				echo "<option value='".strtolower($style)."'>".$style."</option>";
			}
		?>
	</select><br>
	<select name="main_theme">
		<option disabled selected="selected" value="none">Select Main Area Theme...</option>
		<?php
			foreach($themes as $theme) {
				echo "<option value='".strtolower($theme)."'>".$theme."</option>";
			}
		?>
	</select>
	<select name="sub_theme" onchange="subChanged()">
		<option disabled selected="selected" value="none">Select Sub Area Theme...</option>
		<option value="none">None</option>
		<?php
			foreach($themes as $theme) {
				echo "<option value='".strtolower($theme)."'>".$theme."</option>";
			}
		?>
	</select><br>
	<select name="main_day">
		<option disabled selected="selected" value="none">Select Main Area Day/Night...</option>
		<option value="day">Day</option>
		<option value="night">Night</option>
	</select>
	<select name="sub_day" disabled>
		<option disabled selected="selected" value="none">Select Sub Area Day/Night...</option>
		<option value="day">Day</option>
		<option value="night">Night</option>
	</select><br>
	<select name="main_dir">
		<option disabled selected="selected" value="none">Select Main Area Direction...</option>
		<option value="hori">Horizontal</option>
		<option value="vert">Vertical</option>
	</select>
	<select name="sub_dir" disabled>
		<option disabled selected="selected" value="none">Select Sub Area Direction...</option>
		<option value="day">Horizontal</option>
		<option value="night">Vertical</option>
	</select><br>
	<select name="tag_1" onchange="tagChanged()">
		<option disabled selected="selected" value="none">Select 1st Tag...</option>
		<?php
			foreach($tags as $tag) {
				echo "<option value='".strtolower($tag)."'>".$tag."</option>";
			}
		?>
	</select>
	<select name="tag_2" onchange="tagChanged()" disabled>
		<option disabled selected="selected" value="none">Select 2nd Tag...</option>
		<?php
			foreach($tags as $tag) {
				echo "<option value='".strtolower($tag)."'>".$tag."</option>";
			}
		?>
	</select><br>
	<textarea placeholder="Description..." name="description"></textarea><br>
  <input type="submit" value="Upload"">
	<script>
		function subChanged() {
  		var x = document.getElementsByName("sub_theme")[0].value;
			document.getElementsByName("sub_day")[0].disabled = x == "none";
			document.getElementsByName("sub_dir")[0].disabled = x == "none";
		}
		function tagChanged() {
  		var x = document.getElementsByName("tag_1")[0].value;
			document.getElementsByName("tag_2")[0].disabled = x == "none";
		}
		$('select').on('change', function(ev) {
			if(ev.target.value == "none")	$(this).removeClass();
			else $(this).attr('class', '').addClass("notnone");
		});
	</script>
	<?php } ?>
</form>
<?php include("footer.php"); ?>
</body>
</html>