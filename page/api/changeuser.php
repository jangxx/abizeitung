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

$data = json_decode($_POST["data"], true);
if ($data["id"] == "") {
	$error["error"] = "No id given";
	echo json_encode($error);
	exit();
}	

if ($data["delete"] == true) {
	deleteUser($data["id"]);
} else {
	changeUser($data);
}

function deleteUser($id) {
	$query = 'DELETE FROM users WHERE id=' . $id;
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

function changeUser($dataarray) {
	$firstname = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["firstname"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$lastname = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["lastname"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$username = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["username"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$id = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dataarray["id"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$error = false;
	
	if ($firstname != "") {
		$query = 'UPDATE users SET firstname="' . $firstname . '" WHERE id=' . $id;
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if ($result == true) {
			$answer["firstname"] = "success";
		} else {
			$answer["firstname"] = "Unexpected MySQL error.";
			$error = true;
		}
	}
	if ($lastname != "") {
		$query = 'UPDATE users SET lastname="' . $lastname . '" WHERE id=' . $id;
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if ($result == true) {
			$answer["lastname"] = "success";
		} else {
			$answer["lastname"] = "Unexpected MySQL error.";
			$error = true;
		}
	}
	if ($username != "") {
		$query = 'UPDATE users SET username="' . $username . '" WHERE id=' . $id;
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if ($result == true) {
			$answer["username"] = "success";
		} else {
			$answer["username"] = "Unexpected MySQL error.";
			$error = true;
		}
	}
	
	if ($error == true) {
		$answer["error"] = "Unexpected MySQL error.";
	} else {
		$answer["error"] = "success";
	}
	$answer["id"] = $id;
	$answer["procedure"] = "change";
	echo json_encode($answer);
	exit();
}

?>