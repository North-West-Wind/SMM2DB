<?php
include("connect.php");
if($_SERVER["REQUEST_METHOD"] === "POST") {
	$h2 = "";
	if(!isset($_COOKIE["id"])) $h2 = "<h2>You cannot delete this course</h2>";
	else {
		$data = get_data("SELECT uploader FROM courses WHERE id = '{$_POST['id']}'");
    if(count($data["results"]) != 1) $h2 = "<h2>Invalid ID</h2>";
		elseif($data["error"]) $h2 = "<h2>Error deleting course</h2>";
		elseif(isset($_POST["action"]) && $_POST["action"] === "No") header("Location: /course.php?id={$_POST['id']}");
		else {
			$result = $data["results"][0];
			if($_COOKIE["id"] != $result["uploader"]) $h2 = "<h2>You cannot delete this course</h2>";
			else {
				$data = get_data("DELETE FROM courses WHERE id = '{$id}'");
				if($data["error"]) $h2 = "<h2>Error saving the editted course details</h2>";
				else $h2 = "<h2>The course has been deleted! <a href='/' class='redirect'>Click here to return to the main page</a>.</h2>";
			}
		}
	}
}
?>
<html>
    <head>
        <style>
            <?php include("style.css"); ?>
        </style>
        <title>Delete Course</title>
    </head>
    <body>
        <?php include("header.php"); ?>
        <h1>Delete Course</h1>
        <?php
						if($_SERVER["REQUEST_METHOD"] === "POST") {
							echo $h2;
						} elseif(!isset($_GET["id"])) echo "<h2>Invalid ID</h2>";
            else {
							$data = get_data("SELECT `courses`.*, `users`.`username` FROM `courses` LEFT JOIN `users` ON `courses`.`uploader` = `users`.`id` WHERE `courses`.`id` = '".$_GET["id"]."'");
              if(count($data["results"]) != 1) echo "<h2>Invalid ID</h2>";
              elseif($data["error"]) echo "<h2>Error fetching the course details</h2>";
							else foreach($data["results"] as $row) {
								if(!isset($_COOKIE["id"]) || $_COOKIE["id"] != $row["uploader"]) echo "<h2>You cannot delete this course</h2>";
								else {
									echo "<div class='delete'><form class='delete' action='/delete.php' method='post'>
										<label>Delete \"{$data['results'][0]['name']}\"?</label><br>
										<input type='hidden' name='id' value='{$_GET['id']}'>
										<input type='submit' name='action' value='Yes'>
										<input type='submit' name='action' value='No'>
										</form></div>";
								}
              }
            }
        ?>
			<?php include("footer.php"); ?>
    </body>
</html>