<?php
require "constants.php";
session_start();

if ($SETTINGS["disablecomments"] == "true") {
	$return["error"] = "Comments are disabled.";
	echo json_encode($return);
	exit();
}

$data = json_decode($_POST["data"],true);
if ($data["id"] == "") {
	$return["error"] = "No id given.";
	echo json_encode($return);
	exit();
}
if ($data["text"] == "") {
	$return["error"] = "No text given.";
	echo json_encode($return);
	exit();
}
$data["hidden"] = (isset($data["hidden"])) ? $data["hidden"] : false;
$_text = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $data["text"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
$_text = strip_tags($_text,"<b><i><u><strong><p><strike><br>");
if ($_SESSION["SESS_ID"] != "") {
	if ($data["hidden"] == true) {
		$query = 'INSERT INTO comments (from_id, to_id, text, date, hidden) VALUES ("' . $_SESSION["SESS_ID"] . '", "' . ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $data["id"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : "")) . '", "' . $_text . '", "' . date('Y-m-d H:i:s') . '", 1)';
	} else {
		$query = 'INSERT INTO comments (from_id, to_id, text, date) VALUES ("' . $_SESSION["SESS_ID"] . '", "' . ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $data["id"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : "")) . '", "' . $_text . '", "' . date('Y-m-d H:i:s') . '")';
	}
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($result == true) {
		$return["error"] = "success";
		$return["id"] = $data["id"];
		echo json_encode($return);
	} else {
		$return["error"] = "Unexpected MySQL error";
		$return["code"] = 1;
		echo json_encode($return);
	}
}
else {
	$return["error"] = "You don't have permissions to do this. (lvl1)";
	$return["code"] = 1;
	echo json_encode($return);
}
?>