<?php
function mknonce($len = 64) {
	$SNChars = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
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

	if ($separatorPosition === false) // no title extracted
		return null;

	$artist = mb_substr($titleRaw, 0, $separatorPosition, 'UTF-8');
	$title = mb_substr($titleRaw, $separatorPosition + mb_strlen($separator, 'UTF-8'), NULL, 'UTF-8');

	return Array(
		'artist' => $artist,
		'title'  => $title
	);
}

function validateInstanceTag($instanceTag) {
	return (preg_match('/^[a-z0-9]{1,20}$/', $instanceTag) === 1);
}

function getSelectedStation() {
	$stationTag = $_GET['station'];
	if (!validateInstanceTag($stationTag)) {
		return null;
	} else {
		return $stationTag;
	}
}

function setNewBackendPassphrase() {
	$passphrase = mknonce();

	file_put_contents(WM_DATA_DIR.'/runtime/core/access_secret', $passphrase);

	return $passphrase;
}
