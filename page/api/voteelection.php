<?php
require "constants.php";
session_start();

if ($_SESSION["SESS_ID"] == "") {
	$error["error"] = "You don't have permissions to do this. (lvl1)";
	$error["code"] = 1;
	echo json_encode($error);
	exit();	
}

if ($_POST["data"] != "") {
	$data = json_decode($_POST["data"],true);
}

$type = (empty($_GET["type"])) ? "" : $_GET["type"];
if ($type == "") $type = $data["type"];
if ($type == "") $type = "teacher";

switch($type) {
	case "teacher":
		voteForTeacher($data);
		break;
	case "couple":
		voteForCouple($data);
		break;
}
$return["error"] = "Something went wrong";
$return["code"] = 1;
echo json_encode($return);
exit();

function voteForTeacher($dataarray) {
	global $SETTINGS;
	if ($SETTINGS["disableelections"] == "true") {
		$return["error"] = "Elections are disabled";
		$return["code"] = 4;
		echo json_encode($return);
		exit();
	}
	$elec_id = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["elec_id"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$teacher_id = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["teacher_id"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$return["result"]["elec"] = $elec_id;
	$return["result"]["teacher"] = $teacher_id;
	switch ("") {
		case $elec_id:
			$return["error"] = "No election id given";
			$return["code"] = 2;
			echo json_encode($return);
			exit();
			break;
		case $teacher_id:
			$return["error"] = "No teacher id given";
			$return["code"] = 3;
			echo json_encode($return);
			exit();
			break;
	}
	
	$query = 'SELECT * FROM teacherresults WHERE from_id=' . $_SESSION["SESS_ID"] . ' AND elec_id=' . $elec_id;
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$row = mysqli_fetch_array($result);
	if ($row["from_id"] != $_SESSION["SESS_ID"]) {
		$query = 'INSERT INTO teacherresults (from_id, teacher_id, elec_id) VALUES (' . $_SESSION["SESS_ID"] . ', ' . $teacher_id . ', ' . $elec_id . ')';
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	} else {
		$query = 'UPDATE teacherresults SET teacher_id=' . $teacher_id . ' WHERE from_id=' . $_SESSION["SESS_ID"] . ' AND elec_id=' . $elec_id;
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	}
	if ($result == false) {
		$return["error"] = "Unexpected MySQL error";
		$return["code"] = 1;
	} else {
		$return["error"] = "success";
	}
	echo json_encode($return);
	exit();
}

function voteForCouple($dataarray) {
	global $SETTINGS;
	if ($SETTINGS["disablecoupleelections"] == "true") {
		$return["error"] = "Coupleelections are disabled";
		$return["code"] = 4;
		echo json_encode($return);
		exit();
	}
	$person1 = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["person1"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$person2 = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["person2"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$return["result"]["person1"] = $person1;
	$return["result"]["person2"] = $person2;
	switch ("") {
		case $person1:
			$return["error"] = "No person 1 given";
			$return["code"] = 2;
			echo json_encode($return);
			exit();
			break;
		case $person2:
			$return["error"] = "No person 2 given";
			$return["code"] = 3;
			echo json_encode($return);
			exit();
			break;
	}
	
	$query = 'SELECT * FROM gradecoupleresults WHERE from_id=' . $_SESSION["SESS_ID"];
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$row = mysqli_fetch_array($result);
	if ($row["from_id"] != $_SESSION["SESS_ID"]) {
		$query = 'INSERT INTO gradecoupleresults (from_id, person1, person2) VALUES (' . $_SESSION["SESS_ID"] . ', ' . $person1 . ', ' . $person2 . ')';
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	} else {
		$query = 'UPDATE gradecoupleresults SET person1=' . $person1 . ' WHERE from_id=' . $_SESSION["SESS_ID"];
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		$query = 'UPDATE gradecoupleresults SET person2=' . $person2 . ' WHERE from_id=' . $_SESSION["SESS_ID"];
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	}
	if ($result == false) {
		$return["error"] = "Unexpected MySQL error";
		$return["code"] = 1;
	} else {
		$return["error"] = "success";
	}
	echo json_encode($return);
	exit();
}
?>