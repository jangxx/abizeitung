<?php
require "constants.php";
//error_reporting(-1);
session_start();

if ($_SERVER["HTTPS"] != "on") {
	$error["error"] = "You must use https for login";
	$error["code"] = 1;
	echo json_encode($error);
	exit();
}

$data = json_decode($_POST["data"], true);
$data["username"] = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $data["username"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
$data["password"] = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $data["password"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

$query = 'SELECT * FROM users WHERE username="' . strtolower($data["username"]) . '" AND password="' . md5($data["password"]) . '"';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$result = mysqli_fetch_array($result);

if ($result["id"] != "") {
	$_SESSION["SESS_ID"] = $result["id"];
	$_SESSION["SESS_ADMIN"] = $result["admin"];
	$_SESSION["SESS_USERNAME"] = $result["username"];
	//addToLoginList($result["id"]);
	/*if (isset($data["ws"])) {
		if ($data["ws"] == true) {
			$wskey = WSLogin(session_id());
			setcookie("WSSESSKEY", $wskey, 0, '/', 'jangxx.com');
		}
	}*/
	if ($SETTINGS["disablelogin"] == "true" && $_SESSION["SESS_ADMIN"] < 1 && $_SESSION["SESS_ID"] != 388) {
		$error["error"] = "Login is disabled";
		$error["code"] = 3;
		echo json_encode($error);
		exit();
	}
	
	$answer["error"] = "success";
} else {
	$answer["error"] = "Username or password is wrong";
	$answer["code"] = 2;
}
echo json_encode($answer);
exit();

function WSLogin($sid) {
	global $EXEC_DIR;
	global $WS_SECKEY;
	$wskey = generateWSKey();
	$login = json_encode(array('command' => 'login', 'arg1' => $sid, 'arg2' => $wskey, 'arg3' => $EXEC_DIR, 'key' => $WS_SECKEY));
	/*echo $login;
	
	$socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
	socket_connect($socket, "dark-clan.servegame.com", 3658);
	socket_write($socket, $login);
	socket_close($socket);*/
	
	return $wskey;
}

function generateWSKey() {
	$return = "";
	for ($i = 1; $i <= 32; $i++) {
		$rand[0] = rand(48,57);
		$rand[1] = rand(65,90);
		$rand[2] = rand(97,122);
		$return .= chr($rand[rand(0,2)]);
	}
	return $return;
}

function addToLoginList($id) {
	$filename = "../loginlist.txt";
	if (file_exists($filename)) {
		$file = fopen($filename,'r+');
	} else {
		$file = fopen($filename,'x+');
	}
	$size = filesize($filename);
	if ($size > 0) {
		$data = fread($file, $size);
		$users = json_decode($data,true);
	} else {
		$users = array();
	}
	$users[] = $id;
	$users = json_encode($users);
	rewind($file);
	ftruncate($file,0);
	fwrite($file,$users);
	fclose($file);
}
?>