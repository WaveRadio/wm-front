<?php


function csrf_create() {
	if (empty($_SESSION['csrf_token'])) {
		$nonce = mknonce(64);
		$_SESSION['csrf_token'] = $nonce;
	}
	
	return $_SESSION['csrf_token'];
}

function csrf_validate($token) {
	return ($token === $_SESSION['csrf_token']);
}

function csrf_invalidate() {
	unset($_SESSION['csrf_token']);
}