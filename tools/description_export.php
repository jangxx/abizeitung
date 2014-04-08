<?php
if ($_SERVER["HTTPS"] != "on") {
header("location: " . "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]); exit(); }

require "constants.php";

session_start();
if ($_SESSION["SESS_ADMIN"] < 1) {
	echo "Bitte als Admin einloggen.";
	exit();
}

if(!file_exists('export')) mkdir('export');
if(!file_exists('export')) die('Cannot create directory "export"');

$users = array();
$query = 'SELECT * FROM users ORDER BY lastname ASC';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row = mysqli_fetch_array($result)) {
	$users[$row["id"]] = utf8_decode($row["firstname"] . ' ' . $row["lastname"]);
}

//$c = '", "';
$c = "	";

$hFile = fopen("export/" . "desc-" . date('d.m.Y-H:i:s') . ".csv", "w");
fwrite($hFile, "name".$c."dob".$c."lm".$c."wisilw".$c."abi1".$c."abi2".$c."abi3".$c."abi4".$c."g89".$c."ld1".$c."l1".$c."ld2".$c."l2".$c."ld3".$c."l3".$c."ld4".$c."l4".$c."ld5".$c."l5".$c."fp\r\n");

foreach ($users as $uid=>$name) {
	$desc = array(
	"nn" => "", //Nickname
	"dob" => "", //Day of birth
	"mob" => "", //month of birth
	"yob" => "", //year of birth
	"lm" => "", //lebensmotto
	"abm" => "", //was ich schon immer loswerde wollte
	"a1" => "", //Abifach 1
	"a2" => "", //Abifach 2
	"a3" => "", //Abifach 3
	"a4" => "", //Abifach 4
	"g89" => "", //G8/9
	"ld1" => "", //"Lieblings" description 1
	"l1" => "", //"Lieblings" 1
	"ld2" => "", //"Lieblings" description 2
	"l2" => "", //"Lieblings" 2
	"ld3" => "", //"Lieblings" description 3
	"l3" => "", //"Lieblings" 3
	"ld4" => "", //"Lieblings" description 4
	"l4" => "", //"Lieblings" 4
	"ld5" => "", //"Lieblings" description 5
	"l5" => "", //"Lieblings" 5
	);
	$query = 'SELECT * FROM description WHERE user_id=' . $uid;
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	while ($row = mysqli_fetch_array($result)) {
		$desc[$row["type"]] = utf8_decode($row["value"]);
	}
	
	$i = 1;
	$futureplans = "";
	foreach($desc as $key=>$value) {
		if ($a = preg_match_all ("/(f)(\\d+)/is", $key, $matches)) {
			$futureplans .= $i . ") " . $value . " ";
			$i++;
		}
	}
	//echo $futureplans . "<br>";
	
	fwrite($hFile, /*'"' . */$name .
		$c . f($desc["dob"].".".$desc["mob"].".".$desc["yob"]) . 
		$c . f($desc["lm"]) . 
		$c . f($desc["abm"]) . 
		$c . f($desc["a1"]) . 
		$c . f($desc["a2"]) . 
		$c . f($desc["a3"]) . 
		$c . f($desc["a4"]) . 
		$c . f($desc["g89"]) . 
		$c . f(l($desc["ld1"])) . 
		$c . f($desc["l1"]) . 
		$c . f(l($desc["ld2"])) . 
		$c . f($desc["l2"]) . 
		$c . f(l($desc["ld3"])) . 
		$c . f($desc["l3"]) . 
		$c . f(l($desc["ld4"])) . 
		$c . f($desc["l4"]) . 
		$c . f(l($desc["ld5"])) . 
		$c . f($desc["l5"]) .
		$c . f($futureplans) . 
	/*'"' . */"\r\n");
}

fclose($hFile);
echo "Done";
exit();

function f($string) {
	return str_replace('"', "'", $string);
}

function l($String) {
	if (strpos($String,"Lieblings") === false) {
		return "Lieblings" . $String;
	} else {
		return $String;
	}
}