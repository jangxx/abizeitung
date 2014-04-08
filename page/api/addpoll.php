<?php
require "constants.php";
session_start();

if ($_SERVER["HTTPS"] != "on") {
	$error["error"] = "You have to use https for changing the users.";
	echo json_encode($error);
	exit();
}
if ($_SESSION["SESS_ID"] == "") {
	$error["error"] = "You don't have permissions to do this. (lvl1)";
	$error["error_code"] = 1;
	echo json_encode($error);
	exit();	
}
if ($_SESSION["SESS_ADMIN"] != 1) {
	$error["error"] = "You don't have permissions to do this. (lvl2)";
	echo json_encode($error);
	exit();
}

$data = json_decode($_POST["data"],true);
$answer["recieved"] = $data["name"];
if ($data["name"] == "") {
	$error["error"] = "No name given.";
	echo json_encode($error);
	exit();
}
$query = 'INSERT INTO polls (name) VALUES ("' . strip_tags(((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $data["name"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""))) . '")';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
if ($result == true) {
	$answer["error"] = "success";
} else {
	$answer["error"] = "Unexpected MySQL error.";
}
echo json_encode($answer);
exit();
?>