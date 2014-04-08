<?php
require "constants.php";
session_start();

if ($_SESSION["SESS_ID"] == "") {
	$error["error"] = "You don't have permissions to do this. (lvl1)";
	$error["code"] = 1;
	echo json_encode($error);
	exit();	
}

$data = json_decode($_POST["data"], true);

if ($data["delete"] == true) {
	deleteQuote($data["id"]);
} else {
	changeQuote($data);
}

function changeQuote($dataarray) {
	$id = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["id"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	if ($dataarray["text"] != null) $text = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["text"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	if ($dataarray["context"] != null) $context = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["context"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	switch("") {
		case $id:
			$error["error"] = "No id given";
			$error["code"] = 1;
			echo json_encode($error);
			exit();
			break;
		case $text . $context:
			$error["error"] = "No content given";
			$error["code"] = 2;
			echo json_encode($error);
			exit();
			break;
	}
	if ($text != null) {
		$query = 'UPDATE quotes SET text="' . $text . '" WHERE id=' . $id;
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if ($result == false) {
			$answer["error"] = "Unexpected MySQL error";
			$answer["code"] = 1;
		} else {
			$answer["error"] = "success";
		}
	}
	
	if ($context != null) {
		$query = 'UPDATE quotes SET context="' . $context . '" WHERE id=' . $id;
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if ($result == false) {
			$answer["error"] = "Unexpected MySQL error";
			$answer["code"] = 1;
		} else {
			$answer["error"] = "success";
		}
	}
	$answer["id"] = $id;
	$answer["procedure"] = "change";
	echo json_encode($answer);
	exit();
}

function deleteQuote($id) {
	if ($id == "") {
		$error["error"] = "No id given";
		$error["code"] = 1;
		echo json_encode($error);
		exit();
	}	
	$query = 'DELETE FROM quotes WHERE id=' . $id;
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($result == true) {
		$answer["error"] = "success";
	} else {
		$answer["error"] = "Unexpected MySQL error.";
		$answer["code"] = 1;
	}
	$answer["id"] = $id;
	$answer["procedure"] = "delete";
	echo json_encode($answer);
	exit();
}

?>