<?php
require "constants.php";
session_start();

if ($_SESSION["SESS_ID"] == "") {
	$error["error"] = "You don't have permissions to do this. (lvl1)";
	$error["error_code"] = 1;
	echo json_encode($error);
	exit();	
}

echo "<html><head><title>Namen</title></head><body>";

$query = "SELECT * FROM users WHERE hidden=0 ORDER BY lastname ASC";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row = mysqli_fetch_array($result)) {
	echo utf8_decode($row["firstname"]) . " " . utf8_decode($row["lastname"]) . "<br/>";
}

echo "</body></html>";

?>