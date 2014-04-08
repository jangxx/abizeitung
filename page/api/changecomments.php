<?php
require "constants.php";
session_start();

if ($_SESSION["SESS_ID"] == "") {
	$error["error"] = "You don't have permissions to do this (lvl1)";
	$error["code"] = 1;
	echo json_encode($error);
	exit();	
}

$data = json_decode($_POST["data"], true);
if ($data["operation"] == "") {
	$error["error"] = "No operation given";
	$error["code"] = 1;
	echo json_encode($error);
	exit();
} else {
	switch ($data["operation"]) {
		case "importance":
			changeImportance($data["id"],$data["imp"]);
			break;
		case "importance-multi":
			changeImportanceMulti($data["ids"],$data["imps"]);
			break;
		case "delete":
			deleteComment($data["id"]);
			break;
		default:
			$error["error"] = "No valid operation given";
			$error["code"] = 1;
			echo json_encode($error);
			exit();
	}
}
exit();

function deleteComment($id) {
	global $SETTINGS;
	$id = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $id) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	if ($id == "") {
		$error["error"] = "No id given";
		$error["code"] = 1;
		echo json_encode($error);
		exit();
	}
	$query = 'SELECT * FROM comments WHERE id=' . $id;
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$result = mysqli_fetch_array($result);
	if ($result["from_id"] == $_SESSION["SESS_ID"] || ($result["to_id"] == $_SESSION["SESS_ID"] && $SETTINGS["deletecommentstome"] == "true")) {
		$query = 'UPDATE comments SET deleted=1 WHERE id=' . $id;
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		
		if ($result == true) {
			$error["error"] = "success";
			$error["id"] = $id;
		} else {
			$error["error"] = "Unexpected MySQL error.";
			$error["code"] = 1;
		}
	} else {
		$error["error"] = "You don't have permissions to delete other user's comments";
		$error["code"] = 1;
	}
	echo json_encode($error);
}

function changeImportanceMulti($ids,$imps) {
	global $SETTINGS;
	if ($SETTINGS["disablecommentsort"] == "true") {
		$error["error"] = "Comment sorting is disabled";
		$error["code"] = 2;
		echo json_encode($error);
		exit();
	}
	switch (false) {
		case is_array($ids):
			$error["error"] = "No valid ids given";
			$error["code"] = 1;
			echo json_encode($error);
			exit();
		case is_array($imps):
			$error["error"] = "No valid importances given";
			$error["code"] = 1;
			echo json_encode($error);
			exit();
	}
	
	if (count($ids) != count($imps)) {
		$error["error"] = "The arrays are not the same size";
		$error["code"] = 1;
		echo json_encode($error);
		exit();
	}
	
	$error = "success";
	$code = 0;
	$i = 0;
	foreach ($ids as $key=>$id) {
		$query = 'SELECT to_id FROM comments WHERE id=' . $id;
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		$result = mysqli_fetch_array($result);
		if ($result["to_id"] == $_SESSION["SESS_ID"]) {
			$query = 'UPDATE comments SET importance=' . $imps[$key] . ' WHERE id=' . $id;
			$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
			
			if ($result == true) {
				$errors[$i]["error"] = "success";
			} else {
				$errors[$i]["error"] = "Unexpected MySQL error.";
			}
		} else {
			$errors[$i]["error"] = "You don't have permission to change other user's comments";
			$error = "You don't have permissions to change other user's comments";
			$code = 1;
		}
		$i++;
	}
	$return["error"] = $error;
	$return["code"] = $code;
	$return["errors"] = $errors;
	echo json_encode($return);
}

function changeImportance($id,$imp) {
	$id = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $id) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$imp = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $imp) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	switch ("") {
		case $id:
			$error["error"] = "No id given";
			$error["code"] = 1;
			echo json_encode($error);
			exit();
		case $imp:
			$error["error"] = "No importance given";
			$error["code"] = 1;
			echo json_encode($error);
			exit();
	}
	
	$query = 'SELECT to_id FROM comments WHERE id=' . $id;
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$result = mysqli_fetch_array($result);
	if ($result["to_id"] == $_SESSION["SESS_ID"]) {
		$query = 'UPDATE comments SET importance="' . $imp . '" WHERE id=' . $id;
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		
		if ($result == true) {
			$error["error"] = "success";
			$error["code"] = 0;
		} else {
			$error["error"] = "Unexpected MySQL error";
			$error["code"] = 1;
		}
	} else {
		$error["error"] = "You don't have permissions to change other user's comments";
		$error["code"] = 1;
	}
	echo json_encode($error);
}

?>