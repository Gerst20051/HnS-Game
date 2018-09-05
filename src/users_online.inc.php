<?php
$t_stamp = time();
$timeout = ($t_stamp - 600);
$users_id = mysql_real_escape_string($_SESSION['user_id']);
$username = mysql_real_escape_string($_SESSION['username']);

if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != null) 
  $phpself = mysql_real_escape_string($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']);
else 
  $phpself = mysql_real_escape_string($_SERVER['PHP_SELF']);

if (isset($_SESSION['logged']) && ($_SESSION['logged'] == 1)) 
  mysql_query("INSERT INTO users_online VALUES ('$t_stamp','$users_id','$username','$phpself')", $db);
else 
  mysql_query("INSERT INTO users_online VALUES ('$t_stamp','0','guest','$phpself')", $db);

mysql_query("DELETE FROM users_online WHERE timestamp < $timeout", $db);
?>
