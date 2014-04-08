<?php
require "constants.php";
session_start();

if ($_SESSION["SESS_ID"] == "") {
	$error["error"] = "You don't have permissions to do this (lvl1)";
	$error["code"] = 1;
	echo json_encode($error);
	exit();	
}

if ($SETTINGS["disabledescription"] == "true") {
	$error["error"] = "Description is disabled";
	$error["code"] = 2;
	echo json_encode($error);
	exit();	
}

$data = json_decode($_POST["data"], true);
//echo $_POST["data"];
if (count($data) < 1) {
	$error["error"] = "No data given";
	$error["code"] = 1;
	echo json_encode($error);
	exit();	
}
$error = false;
$query = 'SELECT * FROM description WHERE user_id=' . $_SESSION["SESS_ID"];
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$entered = array();
$answer = array();
while ($row = mysqli_fetch_array($result)) {
	$entered[$row["type"]]["value"] = $row["value"];
	$entered[$row["type"]]["id"] = $row["id"];
	$entered[$row["type"]]["already"] = true;
	$entered[$row["type"]]["changed"] = false;
}

foreach($data as $key=>$value) {
	if (checkKey($key)) {
		$entered = fillQueryBuilder($entered, $key, $value);
	} else {
		$answer["errors"][$key] = "Invalid key";
	}
}

foreach($entered as $type=>$info) {
	if ($info["changed"]) {
		if ($info["already"] == true && $info["value"] != "") {
			$query = 'UPDATE description SET value="' . $info["value"] . '" WHERE id=' . $info["id"];
		} elseif ($info["value"] == "") {
			$query = 'DELETE FROM description WHERE id=' . $info["id"];
		} else {
			$query = 'INSERT INTO description (user_id, type, value) VALUES (' . $_SESSION["SESS_ID"] . ', "' . $type . '", "' . $info["value"] . '")';
		}
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if ($result == true) {
			$answer["errors"][$type] = "success";
		} else {
			$error = false;
			$answer["errors"][$type] = "Unexpected MySQL error";
		}
	}
}
if ($error == true) {
	$answer["error"] = "Unexpected MySQL error(s)";
	$answer["code"] = 1;
} else {
	$answer["error"] = "success";
}
echo json_encode($answer);
exit();

function checkKey($key) {
	$checkarray = array(
	"nn" => true, //Nickname
	"dob" => true, //Day of birth
	"mob" => true, //month of birth
	"yob" => true, //year of birth
	"lm" => true, //lebensmotto
	"abm" => true, //was ich schon immer loswerde wollte
	"a1" => true, //Abifach 1
	"a2" => true, //Abifach 2
	"a3" => true, //Abifach 3
	"a4" => true, //Abifach 4
	"g89" => true, //G8/9
	"ld1" => true, //"Lieblings" description 1
	"l1" => true, //"Lieblings" 1
	"ld2" => true, //"Lieblings" description 2
	"l2" => true, //"Lieblings" 2
	"ld3" => true, //"Lieblings" description 3
	"l3" => true, //"Lieblings" 3
	"ld4" => true, //"Lieblings" description 4
	"l4" => true, //"Lieblings" 4
	"ld5" => true, //"Lieblings" description 5
	"l5" => true, //"Lieblings" 5
	);
	if ($key == "fp") {
		return (count($key) > 0);
	} else if ($checkarray[$key] == true) {
		return true;
	} else {
		return false;
	}
}

function fillQueryBuilder($builder, $key, $value) {
	if ($key == "fp") {
		for ($i = 0; $i < count($value); $i++) {
			$_new = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $value[$i]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
			if(!array_key_exists("f" . ($i + 1), $builder)) {
				$builder["f" . ($i + 1)] = array();
			}
			if(!array_key_exists("value", $builder["f" . ($i + 1)])) {
				$builder["f" . ($i + 1)]["changed"] = true;
				$builder["f" . ($i + 1)]["value"] = $_new;
			}
			if ($builder["f" . ($i + 1)]["value"] != $_new) {
				$builder["f" . ($i + 1)]["value"] = $_new;
				$builder["f" . ($i + 1)]["changed"] = true;
			}
			if(!array_key_exists("already", $builder["f" . ($i + 1)])) {
				$builder["f" . ($i + 1)]["already"] = false;
			}
		}
		while (!empty($builder["f" . ($i + 1)])) {
			$builder["f" . ($i + 1)]["value"] = "";
			$builder["f" . ($i + 1)]["changed"] = true;
			$i++;
		}
	} else {
		$_new = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $value) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		if(!array_key_exists($key, $builder)) {
			$builder[$key] = array();
		}
		if(!array_key_exists("value", $builder[$key])) {
			$builder[$key]["changed"] = true;
			$builder[$key]["value"] = $_new;
		}
		if ($builder[$key]["value"] != $_new) {
			$builder[$key]["changed"] = true;
			$builder[$key]["value"] = $_new;
		}
		if(!array_key_exists("already", $builder[$key])) {
			$builder[$key]["already"] = false;
		}
	}
	return $builder;
}
?>