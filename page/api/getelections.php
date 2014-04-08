<?php
require "constants.php";
session_start();

if ($_SESSION["SESS_ID"] == "") {
	$error["error"] = "You don't have permissions to do this. (lvl1)";
	$error["code"] = 1;
	echo json_encode($error);
	exit();	
}

if (!empty($_POST["data"])) {
	$data = json_decode($_POST["data"],true);
} else {
	$data = array("operator" => null);
}

$type = $_GET["type"];
if ($type == "") $type = $data["type"];
if ($type == "") $type = "teacher";

$operator = $data["operator"];
switch($type) {
	case "teacher":
		switch($operator) {
			case "results":
				if (isset($data["deep"])) {
					getTeacherElectionResults($data["deep"]);
				} else {
					getTeacherElectionResults();
				}
				break;
			default:
				getTeacherElectionData();
				break;
		}
		break;
	case "couple":
		switch($operator) {
			case "results":
				getCoupleElectionResults();
				break;
			default:
				getCoupleElectionData();
				break;
		}
		break;
	default:
		$return["error"] = "No type given";
		$return["code"] = 1;
		echo json_encode($return);
		exit();
		break;
}
$return["error"] = "Something went wrong.";
$return["code"] = 1;
echo json_encode($return);
exit();

function getTeacherElectionData() {
	$answer = array();
	
	$query = 'SELECT * FROM teachers ORDER BY lastname ASC';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$teachers = array();
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$teachers[$i]["fullname"] = $row["firstname"] . " " . $row["lastname"];
		$teachers[$i]["image"] = $row["image"];
		$teachers[$i]["id"] = $row["id"];
		$i++;
	}
	
	$query = 'SELECT * FROM teacherresults WHERE from_id=' . $_SESSION["SESS_ID"];
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$teacherresults = array();
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$teacherresults[$i]["teacher"] = $row["teacher_id"];
		$teacherresults[$i]["election"] = $row["elec_id"];
		$i++;
	}

	$query = 'SELECT * FROM teacherelections';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$teacherelections = array();
	$i = 0;
	while($row = mysqli_fetch_array($result)) {
		$teacherelections[$i]["id"] = $row["id"];
		$teacherelections[$i]["name"] = $row["name"];
		$i++;
	}
	
	$answer["teachers"] = $teachers;
	$answer["results"] = $teacherresults;
	$answer["elections"] = $teacherelections;
	$answer["error"] = "success";
	echo json_encode($answer);
	exit();
}

function getTeacherElectionResults($deep = 3) {
	if ($_SESSION["SESS_ADMIN"] != 1) {
		$error["error"] = "You don't have permissions to do this. (lvl2)";
		$error["code"] = 1;
		echo json_encode($error);
		exit();
	}
	
	$query = 'SELECT * FROM teachers ORDER BY lastname ASC';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	global $teachers;
	$teachers = array();
	while ($row = mysqli_fetch_array($result)) {
		$teachers[$row["id"]]["fullname"] = $row["firstname"] . " " . $row["lastname"];
		$teachers[$row["id"]]["firstname"] = $row["firstname"];
		$teachers[$row["id"]]["lastname"] = $row["lastname"];
	}
	
	$query = 'SELECT * FROM teacherresults';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$count = array();
	$avg_count = array();
	$result_arr = array();
	if ($result != false) {
		while ($row = mysqli_fetch_array($result)) {
			if (!isset($count[$row["elec_id"]][$row["teacher_id"]])) {
				$count[$row["elec_id"]][$row["teacher_id"]] = 0;
			}
			$count[$row["elec_id"]][$row["teacher_id"]]++;
			if (!isset($avg_count[$row["elec_id"]])) $avg_count[$row["elec_id"]] = 0;
			$avg_count[$row["elec_id"]]++;
		}
		foreach ($count as $_elec=>$_cur) {
			arsort($count[$_elec]);
		}
		unset($_elec);
	}
	
	foreach ($count as $_id=>$_cur) {
		$i = 0;
		foreach ($_cur as $_teacher=>$_votes) {
			if ($i == $deep) {break;}
			$result_arr[$_id][$i]["name"] = $teachers[$_teacher]["fullname"];
			$result_arr[$_id][$i]["votes"] = $_votes;
			$result_arr[$_id][$i]["percent"] = $_votes / $avg_count[$_id];
			$i++;
		}
	}
	
	$query = 'SELECT * FROM teacherelections';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$_elec["id"] = $row["id"];
		$_elec["name"] = $row["name"];
		$_elec["votes"] = (array_key_exists($row["id"], $avg_count)) ? $avg_count[$row["id"]] : 0;
		$_elec["result"] = (array_key_exists($row["id"], $result_arr)) ? $result_arr[$row["id"]] : array();
		if (!isset($_elec["result"])) {
			$_elec["result"] = array();
		}
		$return[$i] = $_elec;
		$i++;
	}
	if (count($return) == 0) {
		$return = array();
	}
	echo json_encode($return);
	exit();
}

function getCoupleElectionResults() {
	if ($_SESSION["SESS_ADMIN"] != 1) {
		$error["error"] = "You don't have permissions to do this. (lvl2)";
		$error["code"] = 1;
		echo json_encode($error);
		exit();
	}
	$query = 'SELECT * FROM gradecoupleresults';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	global $matches;
	$matches = array();
	$avg_count = 0;
	while ($row = mysqli_fetch_array($result)) {
		if ($row["person1"] == $row["person2"]) continue;
		if(!array_key_exists($row["person1"], $matches)) $matches[$row["person1"]] = array();
		if(!array_key_exists($row["person2"], $matches[$row["person1"]])) $matches[$row["person1"]][$row["person2"]] = 0;
		$matches[$row["person1"]][$row["person2"]]++;
		$avg_count++;
	}
	$cmatches = array();
	$i = 1;
	foreach($matches as $person=>$match) {
		foreach($match as $operson=>$votes) {
			$other = (isset($matches[$operson][$person])) ? $matches[$operson][$person] : 0;
			$cmatches[$i]["votes"] = $votes + $other;
			$cmatches[$i]["person1"] = $person;
			$cmatches[$i]["person2"] = $operson;
			$i++;
			unset($matches[$operson][$person]);
		}
	}
	usort($cmatches, "cmp");
	$cmatches = array_reverse($cmatches);
	
	$query = 'SELECT * FROM users ORDER BY lastname ASC';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$users = array();
	while ($row = mysqli_fetch_array($result)) {
		$users[$row["id"]]["firstname"] = $row["firstname"];
		$users[$row["id"]]["lastname"] = $row["lastname"];
		$users[$row["id"]]["fullname"] = $row["firstname"] . " " . $row["lastname"];
	}
	
	$results = array();
	foreach($cmatches as $pos=>$data) {
		$results[$pos + 1] = array("person1" => $users[$data["person1"]]["fullname"], "person2" => $users[$data["person2"]]["fullname"], "votes" => $data["votes"], "percent" => $data["votes"] / $avg_count);
	}
	
	$answer["results"] = $results;
	$answer["votes"] = $avg_count;
	$answer["error"] = "success";
	echo json_encode($answer);
	exit();
}

function cmp($x, $y) {
	if ($x["votes"] == $y["votes"]) {
		return 0;
	}
	return ($x["votes"] < $y["votes"]) ? -1 : 1;
}

function getCoupleElectionData() {
	$query = 'SELECT * FROM users WHERE hidden=0 ORDER BY lastname ASC';
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$users = array();
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$users[$i]["firstname"] = $row["firstname"];
		$users[$i]["lastname"] = $row["lastname"];
		$users[$i]["fullname"] = $row["firstname"] . " " . $row["lastname"];
		$users[$i]["id"] = $row["id"];
		$i++;
	}
	
	$answer = array();
		
	$query = 'SELECT * FROM gradecoupleresults WHERE from_id=' . $_SESSION["SESS_ID"];
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$row = mysqli_fetch_array($result);
	$coupleresults = array();
	$coupleresults["person1"] = $row["person1"];
	$coupleresults["person2"] = $row["person2"];
	
	$answer["results"] = $coupleresults;
	$answer["users"] = $users;
	$answer["error"] = "success";
	echo json_encode($answer);
	exit();
}
?>