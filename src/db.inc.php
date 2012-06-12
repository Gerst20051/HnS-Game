<?php
define('MYSQL_HOST','localhost');
define('MYSQL_USER','root');
define('MYSQL_PASSWORD','');
define('MYSQL_DATABASE','hns');

$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or die ('<h2>Unable to connect to database Members. Check your connection parameters.</h2>');
mysql_select_db(MYSQL_DATABASE, $db) or die(mysql_error($db));
?>