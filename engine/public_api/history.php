<?php

$station = getSelectedStation();

if (empty($station) || !db_stationExists($station)) {
	json_respond (1, 'Bad station tag', true);
}

$amount = (int)$_GET['amount'];

if ($amount < 0)
		json_respond(3, 'Negative amount is not supported', true);

if ($amount > API_HISTORY_MAX)
	json_respond (2, 'Too big amount requested', true);

if ($amount === 0)
	$amount = (int)API_HISTORY_DEFAULT;

$res = db_getHistoryFor($station, $amount);

if (empty($res)) {
	json_respond(4, 'No records found', true);
} else {
	json_respond(0, $res, true);	
}