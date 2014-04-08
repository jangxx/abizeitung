<?php
require "constants.php";
session_start();

if ($_SESSION["SESS_ID"] == "") {
	$error["error"] = "You don't have permissions to do this. (lvl1)";
	$error["error_code"] = 1;
	echo json_encode($error);
	exit();	
}

if ($_POST["data"] != "") {
	$data = json_decode($_POST["data"],true);
}

$query = 'SELECT * FROM users';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row = mysqli_fetch_array($result)) {
	$users[$row["id"]]["firstname"] = $row["firstname"];
	$users[$row["id"]]["lastname"] = $row["lastname"];
	$users[$row["id"]]["fullname"] = $row["firstname"] . " " . $row["lastname"];
}

if ($data["operator"] == "") {
	$return["error"] = "No operator given.";
	echo json_encode($return);
	exit();
} else {
	switch($data["operator"]) {
		case "specific":
			returnSpecificPolls($data["id"]);
			break;
		case "all":
			if (isset($data["deep"])) {
				returnResults($data["deep"]);
			} else {
				returnResults();
			}
			break;
		case "results":
			returnExactResults($data["id"]);
			break;
		default:
			$return["error"] = "No valid operator given.";
			echo json_encode($return);
			exit();
	}
}
$return["error"] = "Something went wrong.";
echo json_encode($return);
exit();

function returnExactResults($id) {
	if ($_SESSION["SESS_ADMIN"] != 1) {
		$error["error"] = "You don't have permissions to do this. (lvl2)";
		$error["code"] = 1;
		echo json_encode($error);
		exit();
	}
	if ($id == "") {
		$return["error"] = "No id given.";
		$return["code"] = 1;
		echo json_encode($return);
		exit();
	}
	
	global $users;
	$query = 'SELECT * FROM pollresults WHERE poll=' . $id;
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($result != false) {
		while ($row = mysqli_fetch_array($result)) {
			if (!isset($count[$row["user"]])) {
				$count[$row["user"]] = 0;
			}
			$count[$row["user"]]++;
		}
		arsort($count);
	}
	
	$i = 0;
	foreach ($count as $_user=>$_votes) {
		$result_arr[$i]["name"] = $users[$_user]["fullname"];
		$result_arr[$i]["votes"] = $_votes;
		$i++;
	}

	$query = 'SELECT * FROM polls WHERE id=' . $id;
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$i = 0;
	$row = mysqli_fetch_array($result);
	$return["id"] = $row["id"];
	$return["name"] = $row["name"];
	$return["result"] = $result_arr;
	if (!isset($return["result"])) {
		$return["result"] = array();
	}
	echo json_encode($return);
	exit();
}

function returnResults($deep = 3) {
	if ($_SESSION["SESS_ADMIN"] != 1) {
		$error["error"] = "You don't have permissions to do this. (lvl2)";
		$error["code"] = 1;
		echo json_encode($error);
		exit();
	}
	
	global $users;
	$query = 'SELECT * FROM pollresults';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$count = array();
	$avg_count = array();
	if ($result != false) {
		while ($row = mysqli_fetch_array($result)) {
			if (!isset($count[$row["poll"]][$row["user"]])) {
				$count[$row["poll"]][$row["user"]] = 0;
			}
			$count[$row["poll"]][$row["user"]]++;
			if (!isset($avg_count[$row["poll"]])) {
				$avg_count[$row["poll"]] = 0;
			}
			$avg_count[$row["poll"]]++;
		}
		foreach ($count as $_poll=>$_cur) {
			arsort($count[$_poll]);
		}
		unset($_poll);
	}
	
	$result_arr = array();
	foreach ($count as $_id=>$_cur) {
		$i = 0;
		foreach ($_cur as $_user=>$_votes) {
			if ($i == $deep) {break;}
			$result_arr[$_id][$i]["name"] = (array_key_exists($_user, $users)) ? $users[$_user]["fullname"] : "N/A";
			$result_arr[$_id][$i]["votes"] = $_votes;
			$result_arr[$_id][$i]["percent"] = $_votes / $avg_count[$_id];
			$i++;
		}
	}
	
	$query = 'SELECT * FROM polls';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$_poll["id"] = $row["id"];
		$_poll["name"] = $row["name"];
		$_poll["votes"] = (array_key_exists($row["id"], $avg_count)) ? $avg_count[$row["id"]] : 0;
		$_poll["result"] = (array_key_exists($row["id"], $result_arr)) ? $result_arr[$row["id"]] : array();
		if (!isset($_poll["result"])) {
			$_poll["result"] = array();
		}
		$return[$i] = $_poll;
		$i++;
	}
	if (count($return) == 0) {
		$return = array();
	}
	echo json_encode($return);
	exit();
}

function returnSpecificPolls($id) {
	if ($id == "") {
		$return["error"] = "No id given.";
		echo json_encode($return);
		exit();
	}
	global $users;
	$query = 'SELECT * FROM pollresults WHERE sending_user = ' . $_SESSION["SESS_ID"];
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$voted = array();
	while ($row = mysqli_fetch_array($result)) {
		$voted[$row["poll"]] = $row["user"];
	}
	
	$query = 'SELECT * FROM polls';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$_poll = array();
		$_poll["id"] = $row["id"];
		$_poll["name"] = $row["name"];
		if (!empty($voted[$row["id"]]) && $voted[$row["id"]] == $id) {
			$_poll["state"] = "voted";
		} elseif (!empty($voted[$row["id"]])) {
			$_poll["state"] = "disabled";
			$_poll["voted"] = $users[$voted[$row["id"]]]["fullname"];
		} else {
			$_poll["state"] = "okay";
		}
		$return[$i] = $_poll;
		$i++;
	}
	if (count($return) == 0) {
		$return = array();
	}
	echo json_encode($return);
	exit();
}
?>