<?php
session_start();
chdir("/xampp/HomenetSpaces/hnsdesktop/");

if (isset($_GET['id'])) {
$id = trim($_GET['id']);
$username = $_SESSION['username'];
$userid = $_SESSION['user_id'];

if (isset($_GET['action'])) $action = trim($_GET['action']);
if (isset($_GET['data'])) $data = trim($_GET['data']);
$newdata = "$username|$data";
$newplayerslist = "";

switch ($id) {
case 'game':

switch ($action) {
case 'update':

$file = "game/playerlist.txt";

if ($fhandle = fopen($file, 'r')) {
	if (flock($fhandle, LOCK_EX)) {
		$playerslist = trim(fread($fhandle, filesize($file)));
		fclose($fhandle);
	}
}

if ($fhandle = fopen($file, 'w')) {
	if (flock($fhandle, LOCK_EX)) {
		if ($playerslist != "") {
			if (strpos($playerslist, ",") === true) {
				$playerlist = explode(",", $playerslist); $exists = false; $playerinfo = "";
				foreach ($playerlist as $players) {
					$player = explode("|", $players);
					if (in_array($username, $player)) {
						$exists = true; $playerinfo = $players;
					}
				}
				if ($exists === true) {
					while (strpos($newplayerslist, $playerinfo) !== false) {$playerslist = str_replace($playerinfo, $newdata, $playerslist);}
					$newplayerslist = $playerslist;
				} else $newplayerslist = $playerslist . "," . $newdata;
			} else {
				$player = explode("|", $playerslist);
				if (in_array($username, $player)) $newplayerslist = $newdata;
				else $newplayerslist = $playerslist . "," . $newdata;
			}
		} else {
			$newplayerslist = $newdata;
		}
		if (is_writeable($file)) fwrite($fhandle, $newplayerslist);
		echo $newplayerslist;
	}
	fclose($fhandle);
} else {
	echo "x";
}

break;
}

break;
}
}
?>