<?php
require "constants.php";
session_start();

if ($_SESSION["SESS_ID"] == "") {
	$error["error"] = "You don't have permissions to do this. (lvl1)";
	$error["error_code"] = 1;
	echo json_encode($error);
	exit();	
}

$query = 'SELECT * FROM quotes WHERE user_id=' . $_SESSION["SESS_ID"];
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$return = array();
while ($row = mysqli_fetch_array($result)) {
	$_quotes["text"] = $row["text"];
	$_quotes["context"] = $row["context"];
	$_quotes["id"] = $row["id"];
	$return[] = $_quotes;
}
echo json_encode($return);
exit();
?>