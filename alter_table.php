<?php

mysql_connect('localhost', 'agstechn_vauser', 'vikasuser23210') or die('not connected');

mysql_select_db('agstechn_vaoas') or die('database not selected');


$r = mysql_query("RENAME TABLE `order` TO  `orders`");
$retval = mysql_query($r);

$res = mysql_query("SHOW TABLES");

$tables = array();

while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
    $tables[] = "$row[0]";
}
echo '<pre>';
print_r($tables);
echo '</pre>';
?>

