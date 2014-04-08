<?php
require "constants.php";
session_start();

if ($_SESSION["SESS_ID"] == "") {
	$return["error"] = "You don't have permissions to do this (lvl1)";
	$return["code"] = 1;
	echo json_encode($return);
	exit();
}

$query = 'SELECT * FROM description WHERE user_id=' . $_SESSION["SESS_ID"];
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
if ($result == false) {
	$return["error"] = "Unexpected MySQL error";
	echo json_encode($return);
	exit();
}
$return = array();
$_fp = array();
while ($row = mysqli_fetch_array($result)) {
	if (intval(str_replace("f","",$row["type"])) > 0 && intval(str_replace("f","",$row["type"])) < 10) {
		$_fp[intval(str_replace("f","",$row["type"]))] = $row["value"];
	} else {
		$return[$row["type"]] = $row["value"];
	}
}
$_fp["length"] = count($_fp);
$return["fp"] = $_fp;
$return["error"] = "success";
echo json_encode($return);
?>