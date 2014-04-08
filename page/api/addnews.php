<?php
require "constants.php";
session_start();

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
$error["test"] = $data["text"];
if ($data["text"] == "") {
	$error["error"] = "No text given";
	$error["code"] = 1;
	echo json_encode($error);
	exit();
}
$text = str_replace("\n","<br/>",$data["text"]);
$query = 'INSERT INTO news (text, date) VALUES ("' . ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $text) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : "")) . '", "' . date('Y-m-d H:i:s') . '")';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
if ($result == true) {
	$answer["error"] = "success";
} else {
	$answer["error"] = "Unexpected MySQL error";
	$answer["code"] = 1;
}
echo json_encode($answer);
exit();
?>