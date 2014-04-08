<?php
require "constants.php";
session_start();

if ($_SESSION["SESS_ID"] == "") {
	$error["error"] = "You don't have permissions to do this. (lvl1)";
	$error["error_code"] = 1;
	echo json_encode($error);
	exit();	
}

if ($_SESSION["SESS_ADMIN"] != 1) {
	$error["error"] = "You don't have permissions to do this. (lvl2)";
	$error["error_code"] = 1;
	echo json_encode($error);
	exit();	
}

$query = 'SELECT * FROM news ORDER BY date DESC LIMIT 0, 3';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$return = array();
while ($row = mysqli_fetch_array($result)) {
	$_news["text"] = $row["text"];
	$_news["date"] = strtotime($row["date"]);
	$_news["id"] = $row["id"];
	$return[] = $_news;
}
echo json_encode($return);

?>