<?php
session_start();
require_once 'db.inc.php';

if (isset($_GET['id'])) {
$id = trim($_GET['id']);
if (isset($_SESSION['username'])) $username = $_SESSION['username']; else $username = "GUSER";
$userid = $_SESSION['user_id'];
if (isset($_GET['action'])) $action = trim($_GET['action']);
if (isset($_GET['data'])) $data = trim($_GET['data']);

switch ($id) {
case 'game':

switch ($action) {
case 'receive':

$timestamp = time();
$result = mysql_query("SELECT player, info FROM game", $db);
while ($row = mysql_fetch_array($result)) {
if (empty($playerslist)) $playerslist = $row['player'] . ',' . $row['info'];
else $playerslist .= '|' . $row['player'] . ',' . $row['info'];
}

echo $playerslist;

break;
case 'update':

$timestamp = time();
$timeout = $timestamp-30;
mysql_query('INSERT INTO game (timestamp, player, info) VALUES ("' . $timestamp . '", "' . $username . '", "' .  $data . '") ON DUPLICATE KEY UPDATE timestamp = "' . $timestamp . '", info = "' . $data . '"', $db) or die(mysql_error($db));

break;
case 'delete';

$timestamp = time();
$timeout = $timestamp-30;
mysql_query("DELETE FROM game WHERE timestamp < $timeout", $db);

break;
}

break;
}
}
?>