<?php


function csrf_create() {
	$nonce = mknonce(64);
	$_SESSION['csrf_token'] = $nonce;
	return $nonce;
}

function csrf_validate($token) {
	return ($token === $_SESSION['csrf_token']);
}

function csrf_invalidate() {
	unset($_SESSION['csrf_token']);
}