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

$extend = ((int)$_GET['extend'] === 1);

$res = db_getHistoryFor($station, $amount, 'desc', $extend);

if (empty($res)) {
	json_respond(4, 'No records found', true);
} else {
	if ($extend) {
		foreach ($res as &$historyItem) {
			$links = db_getArtistLinks($historyItem['artist_id']);

			$historyItem['artist_links'] = $links;
		}
	}
	
	json_respond(0, $res, true);
}