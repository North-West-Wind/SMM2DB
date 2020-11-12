<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <a href="/">Home</a>
  <a href="/announcements.php">Announcements</a>
  <a href="/upload.php">Upload</a>
  <a href="/search.php">Search</a>
  <a href="/courses.php">Courses</a>
</div>
<script>
function openNav() {
  document.getElementById("mySidenav").style.width = "33%";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}
</script>
<div class="topnav">
<a class="active" href="/">🏡 Home</a>
<span onclick="openNav()" class="menubtn">📄 Menu</span>
<?php
if(!isset($_COOKIE["username"]) || !isset($_COOKIE["id"])) {
?>
<a href="/register.php">🎮 Register</a>
<a href="/login.php">📌 Log In</a>
<?php
} else {
	echo '<a href="/logout.php">🚪 Logout</a>
	<a href="/profile.php?id='.$_COOKIE["id"].'">💳 '.$_COOKIE["username"].'</a>';
}
?>
</div>
<div class="fakeimg"></div>