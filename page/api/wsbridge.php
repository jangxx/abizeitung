<?php
require "constants.php";

if ($_GET["seckey"] != $WS_SECKEY) {
	exit();
}

$func = $_GET["func"];
$args = array();
$i = 1;
while($arg = $_GET["arg" . $i]) {
	$args[$i] = $arg;
	$i++;
}
switch ($func) {
	case "getUserInfo":
		session_id($args[1]);
		session_start();
		if (isset($_SESSION["SESS_ID"])) {
			echo json_encode(array("arg1" => $_SESSION["SESS_ID"], "arg2" => $_SESSION["SESS_ADMIN"], "key" => $WS_SECKEY));
			exit();
		}
		break;
	case "getComment":
		$query = 'SELECT * FROM comments WHERE id=' . ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $args[1]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		$row = mysqli_fetch_array($result);
		$row["key"] = $WS_SECKEY;
		echo json_encode($row);
		exit();
		break;
	default:
		echo "error";
		exit();
		break;
}
exit();
?>