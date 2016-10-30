<?php
$link = mysql_connect('localhost', 'root', 'aztek');
mysql_select_db('p2p', $link);

$sql = ' SELECT *, timestampdiff(MINUTE, `creation_date`, NOW()) FROM `transactions` '
		. ' WHERE answer_date is null and timestampdiff(MINUTE, `creation_date`, NOW())>=15 '
		. ' AND `user_confirmation_date` is not null '
		. ' AND cron_checked_flag=0 ORDER BY `id` DESC';

$query = mysql_query($sql);

while ($row = mysql_fetch_assoc($query)) {
	$data = array(
		'transaction_id' => $row['id'],
		'creation_date' => $row['creation_date']
	);
	$sql = 'INSERT INTO syslog (date, src, descr) VALUES (NOW(), "NO ANSWER", "' . mysql_real_escape_string(serialize($data)) .'")';
	mysql_query($sql);

	$sql = 'UPDATE transactions SET cron_checked_flag=1, cron_checked_date=NOW() WHERE id="' . $row['id'] . '"';
	mysql_query($sql);


}

?>