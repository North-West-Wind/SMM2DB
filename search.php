<?php include("connect.php"); ?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>
<?php include("style.css"); ?>
</style>
<?php
	$page = isset($_GET["page"]) && intval($_GET["page"] > 0) ? $_GET["page"] : 1;
	if(isset($_GET["search"])) echo "<title>Search Courses (Page {$page})</title>";
	else echo "<title>Search Courses</title>";
?>
</head>
<body>
<?php include("header.php"); ?>
<h1><a href='/search.php' class='redirect'>Search Course</a></h1>
<?php
	if(isset($_GET["search"])) {
		$sql = "SELECT id, name, maker, code FROM courses";
		$a = array();
		if(isset($_GET["name"]) && $_GET["name"] !== "") array_push($a, " LOWER(name) LIKE '%".escape_string(strtolower($_GET['name']))."%'");
		if(isset($_GET["maker"]) && $_GET["maker"] !== "") array_push($a, " LOWER(maker) LIKE '%".escape_string(strtolower($_GET['maker']))."%'");
		if(isset($_GET["code"]) && $_GET["code"] !== "") array_push($a, " LOWER(code) LIKE '%".escape_string(strtolower($_GET['code']))."%'");
		if(isset($_GET["time"]) && $_GET["time"] !== "") array_push($a, " time >= {$_GET['time']}");
		if(isset($_GET["published"]) && $_GET["published"] !== "") array_push($a, " published >= '{$_GET['published']}'");
		if(isset($_GET["style"]) && count($_GET["style"]) > 0 && $_GET["style"][0] !== "all") {
			$b = array();
			foreach($_GET["style"] as $style) {
				$style = array_search($style, array_map('strtolower', $styles));
				array_push($b, "style = {$style}");
			}
			if(count($b) > 0) array_push($a, " (".implode(" OR ", $b).")");
		}
		if(isset($_GET["main_theme"]) && count($_GET["main_theme"]) > 0 && $_GET["main_theme"][0] !== "all") {
			$b = array();
			foreach($_GET["main_theme"] as $theme) {
				$theme = array_search($theme, array_map('strtolower', $themes));
				array_push($b, "theme = {$theme}");
			}
			if(count($b) > 0) array_push($a, " (".implode(" OR ", $b).")");
		}
		if(isset($_GET["sub_theme"]) && count($_GET["sub_theme"]) > 0 && $_GET["sub_theme"][0] !== "all") {
			if($_GET["sub_theme"][0] === "none") array_push($a, " sub_theme = NULL");
			else {
				$b = array();
				foreach($_GET["sub_theme"] as $theme) {
					$theme = array_search($theme, array_map('strtolower', $themes));
					array_push($b, "sub_theme = {$theme}");
				}
				if(count($b) > 0) array_push($a, " (".implode(" OR ", $b).")");
			}
		}
		if(isset($_GET["main_day"]) && $_GET["main_day"] !== "none" && $_GET["main_day"] !== "all") {
			if($_GET["main_day"] === "night") array_push($a, " day = 1");
			else array_push($a, " day = 0");
		}
		if(isset($_GET["sub_day"]) && $_GET["sub_day"] !== "none" && $_GET["sub_day"] !== "all") {
			if($_GET["sub_day"] === "night") array_push($a, " sub_day = 1");
			else array_push($a, " sub_day = 0");
		}
		if(isset($_GET["main_dir"]) && $_GET["main_dir"] !== "none" && $_GET["main_dir"] !== "all") {
			if($_GET["main_dir"] === "vert") array_push($a, " direction = 1");
			else array_push($a, " direction = 0");
		}
		if(isset($_GET["sub_dir"]) && $_GET["sub_dir"] !== "none" && $_GET["sub_dir"] !== "all") {
			if($_GET["sub_dir"] === "vert") array_push($a, " sub_direction = 1");
			else array_push($a, " sub_direction = 0");
		}
		if(isset($_GET["tags"]) && count($_GET["tags"]) > 0 && $_GET["tags"][0] !== "all") {
			$b = array();
			foreach($_GET["tags"] as $tag) {
				$tag = array_search($tags, array_map('strtolower', $tag));
				array_push($b, "tag_1 = {$tag} OR tag_2 = {$tag}");
			}
			if(count($b) > 0) array_push($a, " (".implode(" OR ", $b).")");
		}
		if(count($a) > 0) $sql = $sql." WHERE".implode(" AND", $a);
		$offset = ($page - 1) * 25;
		$data = get_data($sql." ORDER BY uploaded DESC LIMIT {$offset}, 25");
		if($data["error"]) echo "<h2>Error searching courses</h2>";
		else {
			?>
			<table class="list">
    		<tr class="top"><th class="namecolumn top">Name</th><th class="makercolumn top">Maker</th><th class="codecolumn top">Code</th></tr>
			<?php
      foreach($data["results"] as $row) {
      	echo '<tr class="courses" onclick="window.location.href = \'course.php?id='.$row["id"].'\';"><td class="namecolumn courses">'.$row["name"].'</td><td class="makercolumn courses">'.$row["maker"].'</td><td class="codecolumn courses">'.$row["code"].'</td></tr>';
      }
			echo '</table><div class="pages">';
			$all = get_data("SELECT COUNT(*) AS total FROM courses".(count($a) > 0 ? " WHERE".implode(" AND", $a) : ""));
			$params = $_GET;
			if(isset($params["page"])) unset($params["page"]);
			$link = array();
			foreach($params as $param) {
				$key = array_keys($params, $param);
				array_push($link, $key[0]."=".$param);
			}
			$link = implode("&", $link);
			if($all["error"]) echo "<a href='/search.php?{$link}&page=1' class='redirect'>1</a>";
			else {
				$pages = ceil($all["results"][0]["total"]/25);
				for($i = 1; $i <= $pages; $i++) echo "<a href='/search.php?{$link}&page={$i}' class='redirect'>{$i}</a>";
			}
			echo "</div>";
		}
	} else {
?>
<form action="/search.php" method="get" id="search">
	<input type="hidden" name="search" value="true">
  <input type="text" name="name" placeholder="By Name..."><br>
  <input type="text" name="maker" placeholder="By Maker..."><br>
  <input type="text" name="code" placeholder="By Code..."><br>
  <input type="text" name="time" placeholder="By Time Limit..."><br>
  <input type="text" name="published" placeholder="By Published Date..." onfocus="(this.type='datetime-local')" onfocusout="(this.type='text')"><br>
	<select name="style[]" size="7" multiple>
		<option disabled selected="selected" value="none">By Style...</option>
		<option value="all">All Styles</option>
		<?php
			foreach($styles as $style) {
				echo "<option value='".strtolower($style)."'>".$style."</option>";
			}
		?>
	</select><br>
	<select name="main_theme[]" size="10" class="theme" multiple>
		<option disabled selected="selected" value="none">By Main Area Theme...</option>
		<option value="all">All Themes</option>
		<?php
			foreach($themes as $theme) {
				echo "<option value='".strtolower($theme)."'>".$theme."</option>";
			}
		?>
	</select>
	<select name="sub_theme[]" size="10" class="theme" onchange="subChanged()" multiple>
		<option disabled selected="selected" value="none">By Sub Area Theme...</option>
		<option value="all">All Themes</option>
		<option value="none">None</option>
		<?php
			foreach($themes as $theme) {
				echo "<option value='".strtolower($theme)."'>".$theme."</option>";
			}
		?>
	</select><br>
	<select name="main_day">
		<option disabled selected="selected" value="none">By Main Area Day/Night...</option>
		<option value="all">All Modes</option>
		<option value="day">Day</option>
		<option value="night">Night</option>
	</select>
	<select name="sub_day" disabled>
		<option disabled selected="selected" value="none">By Sub Area Day/Night...</option>
		<option value="all">All Modes</option>
		<option value="day">Day</option>
		<option value="night">Night</option>
	</select><br>
	<select name="main_dir">
		<option disabled selected="selected" value="none">By Main Area Direction...</option>
		<option value="all">All Directions</option>
		<option value="hori">Horizontal</option>
		<option value="vert">Vertical</option>
	</select>
	<select name="sub_dir" disabled>
		<option disabled selected="selected" value="none">By Sub Area Direction...</option>
		<option value="all">All Directions</option>
		<option value="day">Horizontal</option>
		<option value="night">Vertical</option>
	</select><br>
	<select name="tags[]" size="10" multiple>
		<option disabled selected="selected" value="none">By Tags...</option>
		<option value="all">All Tags</option>
		<?php
			foreach($tags as $tag) {
				echo "<option value='".strtolower($tag)."'>".$tag."</option>";
			}
		?>
	</select><br>
  <input type="submit" value="Search"">
	<script>
		function subChanged() {
  		var x = $("select[name='sub_theme[]']").val();
			document.getElementsByName("sub_day")[0].disabled = x.includes("none");
			document.getElementsByName("sub_dir")[0].disabled = x.includes("none");
		}
		$('select').on('change', function(ev) {
			if(ev.target.value == "none")	$(this).removeClass();
			else $(this).attr('class', '').addClass("notnone");
		});
		var searchForm = document.getElementById('search');
		searchForm.addEventListener('submit', function () {
    	var allInputs = searchForm.getElementsByTagName('input');

    	for (var i = 0; i < allInputs.length; i++) {
        var input = allInputs[i];

        if (input.name && !input.value) {
            input.name = '';
        }
    	}
		});
	</script>
	<?php } ?>
</form>
<?php include("footer.php"); ?>
</body>
</html>