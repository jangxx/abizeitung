<?php
require "constants.php";
session_start();

if ($_SESSION["SESS_ID"] == "") {
	$error["error"] = "You don't have permissions to do this. (lvl1)";
	$error["code"] = 1;
	echo json_encode($error);
	exit();	
}
if ($_SESSION["SESS_ADMIN"] != 1) {
	$error["error"] = "You don't have permissions to do this. (lvl2)";
	$error["code"] = 1;
	echo json_encode($error);
	exit();
}

$data = json_decode($_POST["data"], true);

if ($data["delete"] == true) {
	deleteNews($data["id"]);
} else {
	changeNews($data);
}

function changeNews($dataarray) {
	$id = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["id"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$text = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["text"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	switch("") {
		case $id:
			$error["error"] = "No id given";
			echo json_encode($error);
			exit();
			break;
		case $text:
			$error["error"] = "No text given";
			echo json_encode($error);
			exit();
			break;
	}
	$query = 'UPDATE news SET text="' . $text . '" WHERE id=' . $id;
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($result == false) {
		$answer["error"] = "Unexpected MySQL error.";
	}
	$query = 'UPDATE news SET date="' . date('Y-m-d H:i:s') . '" WHERE id=' . $id;
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($result == false) {
		$answer["error"] = "Unexpected MySQL error.";
	} else {
		$answer["error"] = "success";
	}
	$answer["id"] = $id;
	$answer["procedure"] = "change";
	echo json_encode($answer);
	exit();
}

function deleteNews($id) {
	if ($id == "") {
		$error["error"] = "No id given";
		echo json_encode($error);
		exit();
	}	
	$query = 'DELETE FROM news WHERE id=' . $id;
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($result == true) {
		$answer["error"] = "success";
	} else {
		$answer["error"] = "Unexpected MySQL error.";
	}
	$answer["id"] = $id;
	$answer["procedure"] = "delete";
	echo json_encode($answer);
	exit();
}

?>