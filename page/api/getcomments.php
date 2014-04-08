<?php
require "constants.php";
session_start();

$data = json_decode($_POST["data"],true);
$return = array();
if ($data["id"] == "") {
	$return["error"] = "No id given.";
	echo json_encode($return);
	exit();
}
if ($_SESSION["SESS_ID"] != "") {
$query = 'SELECT * FROM users';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row = mysqli_fetch_array($result)) {
	$users[$row["id"]]["firstname"] = $row["firstname"];
	$users[$row["id"]]["lastname"] = $row["lastname"];
	$users[$row["id"]]["fullname"] = $row["firstname"] . " " . $row["lastname"];
	$users[$row["id"]]["pic"] = $row["pic"];
}
if ($users[$data["id"]] == null) {
	$return["error"] = "The requested user does not exist.";
	echo json_encode($return);
	exit();
}
$query = 'SELECT * FROM comments WHERE to_id="' . ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $data["id"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : "")) . '" ORDER BY importance DESC, date';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$i = 0;
while ($row = mysqli_fetch_array($result)) {
	$_comment = array();
	if(!array_key_exists($row["from_id"], $users)) continue;
	if ($row["deleted"] == 0 and (($row["hidden"] == 0) or ($row["from_id"] == $_SESSION["SESS_ID"]) or ($_SESSION["SESS_ADMIN"] == 1) or (($SETTINGS["showhiddencomments"] == "true") and ($row["to_id"] == $_SESSION["SESS_ID"])))) {
		if ($_SESSION["SESS_ADMIN"] == true or $row["from_id"] == $_SESSION["SESS_ID"]) {
			$_comment["from"] = $users[$row["from_id"]]["fullname"];
		}
		$_comment["date"] = date('d.m.Y',strtotime($row["date"]));
		$_comment["text"] = str_replace("\n","<br/>",$row["text"]);
		$_comment["id"] = $row["id"];
		$_comment["importance"] = $row["importance"];
		$_comment["hidden"] = ($row["hidden"] == 1);
		$_comment["delete"] = ($row["from_id"] == $_SESSION["SESS_ID"] or ($SETTINGS["deletecommentstome"] == "true" and $row["to_id"] == $_SESSION["SESS_ID"]));
		$return["comments"][$i] = $_comment;
		$i++;
	}
}
if (!array_key_exists("comments", $return)) {
	$return["comments"] = array();
}
$return["user"]["id"] = $data["id"];
$return["user"]["firstname"] = $users[$data["id"]]["firstname"];
$return["user"]["lastname"] = $users[$data["id"]]["lastname"];
$return["user"]["fullname"] = $users[$data["id"]]["fullname"];
$return["user"]["pic"] = $users[$data["id"]]["pic"];
$return["error"] = "success";
echo json_encode($return);
}
else {
	$return["error"] = "You don't have permissions to do this. (lvl1)";
	$return["code"] = 1;
	echo json_encode($return);
}
?>