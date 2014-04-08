<?php
require "constants.php";
session_start();

if ($_SESSION["SESS_ID"] != "") {
	$query = 'SELECT * FROM users';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	while ($row = mysqli_fetch_array($result)) {
		$users[$row["id"]]["firstname"] = $row["firstname"];
		$users[$row["id"]]["lastname"] = $row["lastname"];
		$users[$row["id"]]["fullname"] = $row["firstname"] . " " . $row["lastname"];
	}
	$return = array();
	$query = 'SELECT * FROM comments ORDER BY date DESC';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$i = 0;
	while (($row = mysqli_fetch_array($result)) && ($i <= 30)) {
		if (!array_key_exists($row["from_id"], $users) or !array_key_exists($row["to_id"], $users)) {
			continue;
		}
		if ($row["deleted"] == 0 and (($row["hidden"] == 0) or ($row["from_id"] == $_SESSION["SESS_ID"]) or ($_SESSION["SESS_ADMIN"] == 1) or (($SETTINGS["showhiddencomments"] == "true") and ($row["to_id"] == $_SESSION["SESS_ID"])))) {
			if ($_SESSION["SESS_ADMIN"]) {$_comment["from"] = $users[$row["from_id"]]["fullname"];}
			$_comment["to"] = $users[$row["to_id"]]["fullname"];
			$_comment["date"] = date('d.m.Y',strtotime($row["date"]));
			$_comment["text"] = str_replace("\n","<br/>",$row["text"]);
			$_comment["hidden"] = ($row["hidden"] == 1);
			$return["comments"][$i] = $_comment;
			$i++;
		}
	}
	$query = 'SELECT * FROM news ORDER BY date DESC LIMIT 0, 3';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$i = 0;
	while (($row = mysqli_fetch_array($result)) && ($i <= 10)) {
		$_news["text"] = $row["text"];
		$_news["date"] = strtotime($row["date"]);
		$return["news"][$i] = $_news;
		$i++;
	}
	if (!array_key_exists("comments", $return)) {
		$return["comments"] = array();
	}
	if (!array_key_exists("news", $return)) {
		$return["news"] = array();
	}
	echo json_encode($return);
}
else {
	$return["error"] = "You don't have permissions to do this. (lvl1)";
	$return["error_code"] = 1;
	echo json_encode($return);
}
?>