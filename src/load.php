<?php
session_start();
chdir("/xampp/HomenetSpaces/hnsdesktop/");
include ("db.inc.php");

if (isset($_GET['id'])) {
$id = trim($_GET['id']);
$username = $_SESSION['username'];
$userid = $_SESSION['user_id'];

if (isset($_GET['action'])) $action = trim($_GET['action']);
if (isset($_GET['data'])) $data = trim($_GET['data']);

switch ($id) {
case 'game':

switch ($action) {
case 'update':
/*
$query = 'UPDATE hns_desktop SET players = "' . serialize(mysql_real_escape_string($data)) . '" WHERE user_id = ' . $userid;
mysql_query($query, $db) or die(mysql_error());
*/

$file = "playerlist.txt";
$fhandle = fopen($file, "r") or exit("Unable to open file!");
$playerlist = fread($fhandle, filesize($file));
fclose($fhandle);

if ($playerlist == "") {
$playerlist = $_SESSION['username'] . " " . $data;
} else {
$playerslist = explode(",", $playerlist);
foreach ($playerslist as $list) {
if (!in_array($_SESSION['username'], explode(" ", $list))) $playerlist .= "," . $_SESSION['username'] . " " . $data;
}
}

$fhandle = fopen($file, "w");
fwrite($fhandle, $playerlist);
fclose($fhandle);

break;
case 'retrieve':

$file = "playerlist.txt";
$fhandle = fopen($file, "r") or exit("Unable to open file!");
$content = fread($fhandle, filesize($file));
fclose($fhandle);
$content = explode(",", $content);

/*
foreach($content as $player) {
$query = 'SELECT user_id FROM login WHERE username = "' . mysql_real_escape_string($player, $db) . '"';
$result = mysql_query($query, $db) or die(mysql_error($db));

if (mysql_num_rows($result) == 1) {
$row = mysql_fetch_assoc($result);
extract($row);
mysql_free_result($result);
$playerid = $row['user_id'];
}

$query = 'SELECT players FROM hns_desktop WHERE user_id = "' . mysql_real_escape_string($playerid, $db) . '"';
$result = mysql_query($query, $db) or die(mysql_error($db));

if (mysql_num_rows($result) == 1) {
$row = mysql_fetch_assoc($result);
extract($row);
mysql_free_result($result);
echo $row['players'];
}
}
*/

break;
}

break;
}
}

/*
if (isset($_SESSION['logged']) && ($_SESSION['logged'] == 1)) mysql_query('UPDATE hns_desktop SET players = "' . mysql_real_escape_string($players) . '" WHERE user_id = ' . $_SESSION['user_id'], $db);
else mysql_query('UPDATE hns_desktop SET players = "' . mysql_real_escape_string($players) . '" WHERE user_id = ' . $_SESSION['user_id'], $db);

$players = "";
if (isset($_SESSION['logged']) && ($_SESSION['logged'] == 1)) mysql_query('UPDATE hns_desktop SET players = "' . mysql_real_escape_string($players) . '" WHERE user_id = ' . $_SESSION['user_id'], $db);
else mysql_query('UPDATE hns_desktop SET players = "' . mysql_real_escape_string($players) . '" WHERE user_id = ' . $_SESSION['user_id'], $db);
*/
?>