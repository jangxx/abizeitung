<?php
require "constants.php";
session_start();

if ($_SESSION["SESS_ID"] == "") {
	$return["error"] = "You don't have permissions to do this. (lvl1)";
	$return["error_code"] = 1;
	echo json_encode($return);
	exit();
}

$query = 'SELECT * FROM users WHERE id=' . $_SESSION["SESS_ID"];
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
if ($result == false) {
	$return["error"] = "Unexpected MySQL error";
	echo json_encode($return);
	exit();
}
$row = mysqli_fetch_array($result);
$return = array();

//$return["pic"] = str_replace("fb:", "", $row["pic"]);
$return["pic"] = $row["pic"];

$return["error"] = "success";
echo json_encode($return);
?>