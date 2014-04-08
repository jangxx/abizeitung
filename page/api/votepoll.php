<?php
require "constants.php";
session_start();

if ($_SESSION["SESS_ID"] == "") {
	$error["error"] = "You don't have permissions to do this (lvl1)";
	$error["code"] = 1;
	echo json_encode($error);
	exit();	
}

if ($SETTINGS["disablepolls"] == "true") {
	$error["error"] = "Polls are disabled";
	$error["code"] = 2;
	echo json_encode($error);
	exit();
}

$data = json_decode($_POST["data"],true);
if (!isset($data["user"])) {
	$return["error"] = "No user given";
	$return["code"] = 1;
	echo json_encode($return);
	exit();
}
if (!isset($data["poll"])) {
	$return["error"] = "No poll given";
	$return["code"] = 1;
	echo json_encode($return);
	exit();
}
if (!isset($data["operation"])) {
	$return["error"] = "No operation given";
	$return["code"] = 1;
	echo json_encode($return);
	exit();
} else {
	switch ($data["operation"]) {
		case "vote":
			votePoll($data["user"],$data["poll"]);
			break;
		case "unvote":
			unvotePoll($data["user"],$data["poll"]);
			break;
		case "auto":
			autoVote($data["user"],$data["poll"]);
			break;
		default:
			$return["error"] = "No valid operation given.";
			echo json_encode($return);
			exit();
	}
}
$return["error"] = "Something went wrong.";
echo json_encode($return);
exit();

function autoVote($user,$poll) {
	$user = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $user) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$poll = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $poll) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	switch ("") {
		case $user:
			$return["error"] = "No user id given.";
			echo json_encode($return);
			exit();
		case $poll:
			$return["error"] = "No poll id given";
			echo json_encode($return);
			exit();
	}	
	$query = 'SELECT * FROM pollresults WHERE user=' . $user . ' AND poll=' . $poll . ' AND sending_user=' . $_SESSION["SESS_ID"];
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$result = mysqli_fetch_array($result);
	if ($result == false) {
		votePoll($user,$poll,true);
	} else {
		unvotePoll($user,$poll,true);
	}
}

function unvotePoll($user,$poll,$checked = false) {
	if ($checked == false) {
		$user = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $user) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		$poll = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $poll) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		switch ("") {
			case $user:
				$return["error"] = "No user id given.";
				echo json_encode($return);
				exit();
			case $poll:
				$return["error"] = "No poll id given";
				echo json_encode($return);
				exit();
		}
		$query = 'SELECT * FROM pollresults WHERE user=' . $user . ' AND poll=' . $poll . ' AND sending_user=' . $_SESSION["SESS_ID"];
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		$result = mysqli_fetch_array($result);
		if ($result == false) {
			$return["error"] = "You haven't voted on that poll, yet.";
			echo json_encode($return);
			exit();
		}
	}
	
	$query = 'DELETE FROM pollresults WHERE user=' . $user . ' AND poll=' . $poll . ' AND sending_user=' . $_SESSION["SESS_ID"];
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($result == true) {
		$return["error"] = "success";
		$return["operation"] = "unvote";
		$return["poll"] = $poll;
	} else {
		$return["error"] = "Unexpected MySQL error.";
	}
	echo json_encode($return);
	exit();
}	

function votePoll($user, $poll, $checked = false) {
	if ($checked == false) {
		$user = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $user) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		$poll = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $poll) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		switch ("") {
			case $user:
				$return["error"] = "No user id given.";
				echo json_encode($return);
				exit();
			case $poll:
				$return["error"] = "No poll id given";
				echo json_encode($return);
				exit();
		}
		$query = 'SELECT * FROM pollresults WHERE user=' . $user . ' AND poll=' . $poll . ' AND sending_user=' . $_SESSION["SESS_ID"];
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		$result = mysqli_fetch_array($result);
		if ($result != false) {
			$return["error"] = "You already voted on that poll.";
			echo json_encode($return);
			exit();
		}
	}
	
	$query = 'INSERT INTO pollresults (poll, user, sending_user) VALUES (' . $poll . ', ' . $user . ', ' . $_SESSION["SESS_ID"] . ')';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($result == true) {
		$return["error"] = "success";
		$return["operation"] = "vote";
		$return["poll"] = $poll;
	} else {
		$return["error"] = "Unexpected MySQL error.";
	}
	echo json_encode($return);
	exit();
}
?>