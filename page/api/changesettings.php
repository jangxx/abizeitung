<?php
require "constants.php";
session_start();

if ($_SERVER["HTTPS"] != "on") {
	$error["error"] = "You have to use https for changing the settings";
	$error["code"] = 1;
	echo json_encode($error);
	exit();
}
if ($_SESSION["SESS_ID"] == "") {
	$error["error"] = "You don't have permissions to do this. (lvl1)";
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
		case "password":
			changePW($data);
			break;
		case "pic":
			changePic($data);
			break;
		case "admin":
			changeAdminSettings($data["key"],$data["value"]);
			break;
		default:
			$error["error"] = "No valid operation given";
			$error["code"] = 1;
			echo json_encode($error);
			exit();
	}
}
exit();

function changePic($dataarray) {
	$pic = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["pic"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	
	if ($pic == "") {
		$query = 'UPDATE users SET pic="" WHERE id=' . $_SESSION["SESS_ID"];
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		
		if ($result == true) {
			$error["error"] = "success";
			$error["code"] = 2;
		} else {
			$error["error"] = "Unexpected MySQL error";
			$error["code"] = 1;
		}
		echo json_encode($error);
		exit();
	}
	
	/*if ( !( ( (substr($pic,strrpos($pic,".") + 1) == "jpg") || (substr($pic,strrpos($pic,".") + 1) == "png") ) && ( (strlen($pic) - strrpos($pic,".") - 1) <= 4 ) ) ) {
		if (strrpos($pic,"/") !== false) {
			$pic = substr($pic,strrpos($pic,"/") + 1);
		}
		$pic = "fb:" . $pic;
	}*/
	
	$query = 'UPDATE users SET pic="' . $pic . '" WHERE id=' . $_SESSION["SESS_ID"];
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	
	if ($result == true) {
		$error["error"] = "success";
		$error["code"] = 2;
	} else {
		$error["error"] = "Unexpected MySQL error";
		$error["code"] = 1;
	}
	echo json_encode($error);
}

function changeAdminSettings($key,$value) {
	if ($_SESSION["SESS_ADMIN"] != 1) {
		$error["error"] = "You don't have permissions to do this. (lvl2)";
		echo json_encode($error);
		exit();
	}
	$key = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $key) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$value = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $value) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	switch ("") {
		case $key:
			$error["error"] = "No key given.";
			echo json_encode($error);
			exit();
		case $value:
			$error["error"] = "No value given.";
			echo json_encode($error);
			exit();
	}
	$query = 'UPDATE misc SET value="' . $value . '" WHERE `key`="' . $key . '"';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($result != false) {
		$return["error"] = "success";
		$return["key"] = $key;
		$return["value"] = $value;
	} else {
		$return["error"] = "Unexpected MySQL error.";
		$return["code"] = 1;
	}
	echo json_encode($return);
}

function changePW($dataarray) {
	$oldpw = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["oldpw"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$newpw = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["newpw"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$retpw = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["retpw"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	switch ("") {
		case $oldpw:
			$error["error"] = "No old password given";
			$error["code"] = 1;
			echo json_encode($error);
			exit();
		case $newpw:
			$error["error"] = "No new password given";
			$error["code"] = 1;
			echo json_encode($error);
			exit();
		case $retpw:
			$error["error"] = "No retyped password given";
			$error["code"] = 1;
			echo json_encode($error);
			exit();
	}
	
	if ($newpw != $retpw) {
		$error["error"] = "The passwords do not match";
		$error["code"] = 1;
		echo json_encode($error);
		exit();
	}
	
	$query = 'SELECT password FROM users WHERE id=' . $_SESSION["SESS_ID"];
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$result = mysqli_fetch_array($result);
	
	if ($result["password"] != md5($oldpw)) {
		$error["error"] = "The old password was entered wrong";
		$error["code"] = 2;
		echo json_encode($error);
		exit();
	}
	
	$query = 'UPDATE users SET password="' . md5($newpw) . '" WHERE id=' . $_SESSION["SESS_ID"];
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	
	if ($result == true) {
		$error["error"] = "success";
		$error["code"] = 3;
	} else {
		$error["error"] = "Unexpected MySQL error";
		$error["code"] = 1;
	}
	echo json_encode($error);
}

?>