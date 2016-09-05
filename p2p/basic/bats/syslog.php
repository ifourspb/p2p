<?php
$link = mysql_connect('localhost', 'root', 'aztek');
mysql_select_db('p2p', $link);

$file = file('/tmp/logwatcher');
$z = -1;
foreach ($file as $k => $str) {
	//echo "\n" . $str;
	if (strstr($str, 'End ---')) {
		$ends[] = $str;
		continue;
	}
	if (strstr($str, 'Begin ---')) {
		$begins[] = $str;
		$z++;
		continue;
	}
	if (strstr($str, ' Logwatch End')) {
		continue;
	}
	if (trim($str)!='' ) {
		$data[$z][] = trim($str);
	}
}

foreach ($data as $k => $a) {
	$sys[$k] = '';
	foreach ($a as $k2 => $v2) {
		$sys[$k] .= $v2 . "\n";
	}
}

foreach ($begins as $k => $str) {
	$key = trim(str_replace(array(' ', '-', 'Begin'), '', $str));
	$sql = 'INSERT INTO syslog (date, src, tags, descr) VALUES (NOW(), "' . mysql_real_escape_string($key). '", "", "' . mysql_real_escape_string($sys[$k]) .'") ';
	mysql_query($sql);

}

//var_dump($sys);