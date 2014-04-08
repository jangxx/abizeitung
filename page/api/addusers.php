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

$data = json_decode($_POST["data"],true);
if (count($data["firstnames"]) == 0) {
	$error["error"] = "No firstnames given";
	echo json_encode($error);
	exit();
}
if (count($data["lastnames"]) == 0) {
	$error["error"] = "No lastnames given";
	echo json_encode($error);
	exit();
}
if (count($data["firstnames"]) <> count($data["lastnames"])) {
	$error["error"] = "The number of firstnames and lastnames does not match";
	echo json_encode($error);
	exit();
}
$error = false;
for ($i = 0; $i < count($data["firstnames"]); $i++) {
	$password = generatePassword(8);
	$username = buildUsername($data["firstnames"][$i],$data["lastnames"][$i]);
	$query = 'INSERT INTO users (firstname, lastname, username, password, default_password) VALUES ("' . $data["firstnames"][$i] . '", "' . $data["lastnames"][$i] . '", "' . $username . '", "' . md5($password) . '", "' . $password . '")';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($result == true) {
		$answer["errors"][$i] = "success";
		$answer["firstnames"][$i] = str_replace("\n","",$data["firstnames"][$i]);
		$answer["lastnames"][$i] = str_replace("\n","",$data["lastnames"][$i]);
		$answer["passwords"][$i] = $password;
		$answer["usernames"][$i] = $username;
	} else {
		$error = true;
		$answer["errors"][$i] = "Unexpected MySQL error.";
	}
}
if ($error == true) {
	$answer["error"] = "Unexpected MySQL error";
} else {
	$answer["error"] = "success";
}
echo json_encode($answer);
exit();

function generatePassword($length) {
	$return = "";
	for ($i = 1; $i <= $length; $i++) {
		$rand[0] = rand(48,57);
		$rand[1] = rand(65,90);
		$rand[2] = rand(97,122);
		$return .= chr($rand[rand(0,2)]);
	}
	return $return;
}

function buildUsername($firstname, $lastname) {
	$firstname = strtolower($firstname);
	$lastname = strtolower($lastname);
	$username = str_replace(" ","",$firstname) . "." . str_replace(" ","",$lastname);
	$_username = $username;
	
	$query = 'SELECT * FROM users';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$i = 1;
	while ($row = mysqli_fetch_array($result)) {
		if ($row["username"] == $_username) {
			$i++;
			$_username = $username . $i;
		}
	}
	return $_username;
}
?>