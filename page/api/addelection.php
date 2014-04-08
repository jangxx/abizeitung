<?php
require "constants.php";
session_start();

if ($_SERVER["HTTPS"] != "on") {
	$error["error"] = "You have to use https for changing the users";
	$error["code"] = 1;
	echo json_encode($error);
	exit();
}
if ($_SESSION["SESS_ID"] == "") {
	$error["error"] = "You don't have permissions to do this (lvl1)";
	$error["code"] = 1;
	echo json_encode($error);
	exit();	
}
if ($_SESSION["SESS_ADMIN"] != 1) {
	$error["error"] = "You don't have permissions to do this (lvl2)";
	$error["code"] = 1;
	echo json_encode($error);
	exit();
}

$data = json_decode($_POST["data"],true);
if ($data["name"] == "") {
	$error["error"] = "No name given";
	$error["code"] = 1;
	echo json_encode($error);
	exit();
}
$query = 'INSERT INTO teacherelections (name) VALUES ("' . strip_tags(((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $data["name"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""))) . '")';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
if ($result == true) {
	$answer["error"] = "success";
} else {
	$answer["error"] = "Unexpected MySQL error.";
	$answer["code"] = 1;
}
echo json_encode($answer);
exit();
?>