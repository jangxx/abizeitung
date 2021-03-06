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
if ($data["id"] == "") {
	$error["error"] = "No id given";
	$error["code"] = 1;
	echo json_encode($error);
	exit();
}	

if ($data["delete"] == true) {
	deleteElection($data["id"]);
} else {
	$error["error"] = "You can only delete with this function.";
	$error["code"] = 1;
	echo json_encode($error);
	exit();
}

function deleteElection($id) {
	$query = 'DELETE FROM teacherelections WHERE id=' . $id;
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