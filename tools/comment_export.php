<?php
if ($_SERVER["HTTPS"] != "on") {
header("location: " . "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]); exit(); }

require "constants.php";

session_start();
if ($_SESSION["SESS_ADMIN"] < 1) {
	echo "Bitte als Admin einloggen.";
	exit();
}


if(!file_exists('export')) mkdir('export');
if(!file_exists('export')) die('Cannot create directory "export"');

$users = array();
$query = 'SELECT * FROM users ORDER BY lastname ASC';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row = mysqli_fetch_array($result)) {
	$users[$row["id"]] = utf8_decode($row["lastname"] . ", " . $row["firstname"]);
}

$dir = "export/comments-" . date('d.m.Y-H:i:s');
mkdir($dir);

$comments = array();

$query = "SELECT * FROM comments WHERE deleted=0";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$ccount = 0;
while ($row = mysqli_fetch_array($result)) {
	$ccount++;
	if (!array_key_exists($row["to_id"], $comments)) {
		$comments[$row["to_id"]] = array();
	}
	$comments[$row["to_id"]][$row["importance"]] = utf8_decode($row["text"]);
}
echo "Comments read: " . $ccount;

foreach($users as $id=>$name) {
	if (!array_key_exists($id, $comments)) $comments[$id] = array();
	krsort($comments[$id]);
	$hFile = fopen($dir . "/" . utf8_encode($name) . ".txt","w");
	foreach($comments[$id] as $text) {
		$text = fix($text);
		fwrite($hFile, "- " . $text . "\r\n");
	}
	fclose($hFile);
}

function fix($text) {
	$text = str_replace("&lsquo;","'",$text);
	$text = str_replace("&rdquo;", '"', $text);
	$text = strip_tags($text);
	return $text;
}

?>