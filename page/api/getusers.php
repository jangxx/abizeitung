<?php
require "constants.php";
session_start();

$searchString = "";
if (!empty($_POST["data"])) {
	$data = json_decode($_POST["data"],true);
	if ($data["search"]) {
		$searchString = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $data["search"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	}
}
if ($_SESSION["SESS_ID"] != "") {
	if ($searchString == "") {
		$query = 'SELECT * FROM users ORDER BY lastname ASC';
	} else {
		$searchTerms = explode(" ",$searchString);
		$querySearch = "";
		foreach ($searchTerms as $term) {
			$querySearch = $querySearch . '((firstname LIKE "%' . $term . '%") OR (lastname LIKE "%' . $term . '%")) AND ';
		}
		$querySearch = substr_replace($querySearch, "", -5);
		$query = 'SELECT * FROM users WHERE ' . $querySearch . ' ORDER BY lastname ASC';
	}
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		if ($row["hidden"] != "1") {
			$_user["id"] = $row["id"];
			$_user["firstname"] = $row["firstname"];
			$_user["lastname"] = $row["lastname"];
			$_user["username"] = $row["username"];
			if ( ($_SESSION["SESS_ADMIN"] == 1) && ($row["password"] == md5($row["default_password"])) ) {
				$_user["defaultpassword"] = $row["default_password"];
			} else {
				$_user["defaultpassword"] = null;
			}
			$return[$i] = $_user;
			$i++;
		}
	}
	if ($return == null) {
		$return["error"] = "No results found";
	}
	echo json_encode($return);
}
else {
	$return["error"] = "You don't have permissions to do this. (lvl1)";
	$return["error_code"] = 1;
	echo json_encode($return);
}
?>