<?php
function mknonce($len = 64) {
	$SNChars = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM-_+=/';
	$SNCCount = strlen($SNChars);
	$s = '';
	while (strlen($s) < $len) {
		$s .= $SNChars[random_int(0, $SNCCount-1)];
	}
	return $s;
}

function enhashPassword ($username, $password, $type = 'broadcast') {

	$salt = ($type === 'admin' ? ADMIN_HASH_SALT : BROADCAST_HASH_SALT);

	return hash('sha256', hash('sha256', $password).$salt.$username);
}

function splitMetadata($titleRaw) {

	$separator = ' - ';
	$separatorPosition = mb_strpos($titleRaw, $separator, 0, 'UTF-8');

	if ($separatorPosition === -1) // no title extracted
		return null;

	$artist = mb_substr($titleRaw, 0, $separatorPosition, 'UTF-8');
	$title = mb_substr($titleRaw, $separatorPosition + mb_strlen($separator, 'UTF-8'), NULL, 'UTF-8');

	return Array(
		'artist' => $artist,
		'title'  => $title
	);
}

function getSelectedStation() {
	$stationTag = $_GET['station'];
	if (preg_match('/^[a-z]{1,20}$/', $stationTag) !== 1) {
		return null;
	} else {
		return $stationTag;
	}
}