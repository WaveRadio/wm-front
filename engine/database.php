<?php

require_once (WORK_PATH.'/pgsql.php');

function db_searchArtist($artist_name) {
	$res = db_sqlQuery('
		select
			artist.*,
			artist_city.city_name as artist_city_name
		from
			artist
		left join
			artist_alias
			using (artist_id)
		inner join
			artist_city
			on artist.artist_city = artist_city.city_id
		where lower(artist.artist_name) = lower(?) or lower(artist_alias.alias_text) = lower(?)', $artist_name, $artist_name)->fetch();

	return $res;
}

function db_addArtist($artist_name) {
	db_sqlQuery('INSERT INTO artist (artist_name) VALUES (?)', $artist_name);

	return pgsql_getLastInsertId('seq_artist_id');
}

function db_addArtistAlias ($artist_id, $alias_text) {
	return db_sqlQuery('INSERT INTO artist_alias (artist_id, alias_text) VALUES (?, ?)', $artist_id, $artist_name);
}

function db_addHistory($station_tag, $artist_id, $track_title, $track_path = null) {
	return db_sqlQuery('INSERT INTO track_history (station_tag, artist_id, track_title, track_path) VALUES (?, ?, ?, ?)', $station_tag, $artist_id, $track_title, $track_path);
}

function db_stationExists($station_tag) {
	$res = db_sqlQuery('select count(*) as cnt from stations where station_tag = ?', $station_tag)->fetch();

	return ((int)$res['cnt'] > 0);
}

function db_getArtistLinks($artist_id) {
	return db_sqlQuery("SELECT link_text FROM artist_link WHERE artist_id = ?", $artist_id)->fetchAll();
}

function db_addArtistLink($artist_id, $link_text) {
	return db_sqlQuery("INSERT INTO artist_link (artist_id, link_text) VALUES (?, ?)", $artist_id, $link_text);
}

function db_removeAllArtistLinks($artist_id) { // USE WITH CARE!
	return db_sqlQuery("DELETE FROM artist_link WHERE artist_id = ?", $artist_id);
}

function db_setArtistCity($artist_id, $city_id) {
	return db_sqlQuery('UPDATE artist SET artist_city = ? WHERE artist_id = ?', $city_id, $artist_id);
}

function db_searchArtistCity($city_name) {
	return db_sqlQuery('select * from artist_city where lower(city_name) = lower(?)', $city_name)->fetch();
}

function db_addArtistCity($city_name) {
	db_sqlQuery("INSERT INTO artist_city (city_name) VALUES (?)", $city_name);

	return pgsql_getLastInsertId('seq_artist_city_id'); 
}

function db_getHistoryFor($station_tag, $amount, $order = 'desc', $addArtistId = false) {
	$order = ($order === 'desc') ? $order : 'asc'; // prevent wrong values
	$amount = (int)$amount;

	return db_sqlQuery(
	'select
		artist.artist_name as artist,
		track_title,
		unix_timestamp(history_timestamp) as start_time'

		.($addArtistId ? ', artist.artist_id as artist_id ' : ' ').

	'from
		track_history
	inner join
		artist
	using
		(artist_id)
	where
		station_tag = ?
	order by
		history_timestamp '. $order .'
	limit
		?', $station_tag, $amount)->fetchAll();
}

function db_adminUserExists($user_name) {
	$res = db_sqlQuery('SELECT count(*) as cnt from admin_user where user_name = ?', $user_name)->fetch();

	return ((int)$res['cnt'] > 0);
}

function db_checkAdminUser($user_name, $user_hash) {
	$res = db_sqlQuery('SELECT * FROM admin_user WHERE user_name = ? AND user_hash = ?', $user_name, $user_hash)->fetch();

	return (empty($res) ? false : $res);
}

function db_addAdminUser($user_name, $user_hash) {
	return db_sqlQuery('INSERT INTO admin_user (user_name, user_hash) VALUES (?, ?)', $user_name, $user_hash);
}

function db_setAdminUserHash($user_name, $user_hash) {
	return db_sqlQuery('UPDATE admin_user SET user_hash = ? WHERE user_name = ?', $user_hash, $user_name);
}

function db_checkBroadcastUser($user_hash, $station_tag) {
	$res = db_sqlQuery('SELECT * FROM broadcast_user WHERE user_hash = ? AND station_tag = ?', $user_hash, $station_tag)->fetch();

	return (empty($res) ? false : $res);
}

function db_broadcastUserExists($user_name, $station_tag) {
	$res = db_sqlQuery('SELECT count(*) as cnt from broadcast_user where user_name = ? AND station_tag = ?', $user_name, $station_tag)->fetch();

	return ((int)$res['cnt'] > 0);
}

function db_setBroadcastUserHash($user_name, $station_tag, $user_hash) {
	return db_sqlQuery('UPDATE broadcast_user SET user_hash = ? WHERE user_name = ? AND station_tag = ?', $user_hash, $user_name, $station_tag);
}