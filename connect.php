<?php
function get_data($query) {
	$data = file_get_contents("https://NWWDLC-1.northwestwind.repl.co/api/smm2db/".rawurlencode($query)."/".getenv("APIKEY"));
	$decoded = json_decode($data, true);
	return $decoded;
}
function escape_string($string) {
	$data = file_get_contents("https://NWWDLC-1.northwestwind.repl.co/api/escape/".rawurlencode($string));
	return $data;
}
function sendmail($address, $username, $fc, $link) {
	file_get_contents("https://NWWDLC-1.northwestwind.repl.co/api/smm2db/sendmail/".rawurlencode($address)."/".rawurlencode($username)."/".rawurlencode($fc)."/".rawurlencode($link)."/".getenv("APIKEY"));
}

$styles = array("Super Mario Bros.", "Super Mario Bros. 3", "Super Mario World", "New Super Mario Bros. U", "Super Mario 3D World");
$tags = array("None", "Standard", "Puzzle-solving", "Speedrun", "Autoscroll", "Auto-Mario", "Short and Sweet", "Multiplayer Versus", "Themed", "Music", "Art", "Technical", "Shooter", "Boss battle", "Single player", "Link");
$themes = array("Ground", "Underground", "Underwater", "Desert", "Snow", "Sky", "Forest", "Ghost House", "Airship", "Castle");
?>