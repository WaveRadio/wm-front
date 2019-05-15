<?php

if ($route[1] == 'logout') {
	session_destroy();
	header ('HTTP/1.1 302 Logged off');
	header ('Location: /auth');
	die();
}

if ($route[1] == 'go') {

	$login = strtolower($_POST['username']);
	$password = enhashPassword($login, $_POST['password'], 'admin');

	$userData = db_checkAdminUser($login, $password);
	if ($userData !== false) {
		$nonce = mknonce(64);

		$_SESSION['user_nonce'] = $nonce;
		$_SESSION['user_id'] = (int)$userData['user_id'];
		$_SESSION['user_login'] = $$userData['user_name'];
		header ('HTTP/1.1 302 Auth OK');
		header ('Location: /');
		die();
	}
	else {
		displayAuth();
	}
}
else
	displayAuth();