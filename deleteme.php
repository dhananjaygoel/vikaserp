<?php

mysql_connect('localhost', 'agstechn_vauser', 'vikasuser23210') or die('not connected');

mysql_select_db('agstechn_vaoas') or die('database not selected');

$res = mysql_query("SHOW TABLES");

$tables = array();

while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
    $tables[] = "$row[0]";
}
echo '<pre>';
print_r($tables);
echo '</pre>';

$length = count($tables);

for ($i = 0; $i < $length; $i++) {
//    $res = "DELETE FROM $tables[$i]";
    $res = "DROP TABLE IF EXISTS ".$tables[$i];
    mysql_query($res);
    echo $res;
}
?>
