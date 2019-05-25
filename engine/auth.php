<?php

if ($route[1] == 'logout') {

	if (!csrf_validate($_GET['token'])) {
		die(); // TODO: proper error message
	}

	session_destroy();
	header ('HTTP/1.1 302 Logged off');
	header ('Location: /auth');
	die();
}

if ($route[1] == 'go') {

	if (!csrf_validate($_POST['csrf_token'])) {
		displayAuth(csrf_create());
	}

	$login = strtolower($_POST['username']);
	$password = enhashPassword($login, $_POST['password'], 'admin');

	$userData = db_checkAdminUser($login, $password);
	if ($userData !== false) {
		$nonce = mknonce(64);

		$_SESSION['user_nonce'] = $nonce;
		$_SESSION['user_id'] = (int)$userData['user_id'];
		$_SESSION['user_login'] = $userData['user_name'];
		header ('HTTP/1.1 302 Auth OK');
		header ('Location: /');
		die();
	}
	else {
		displayAuth(csrf_create());
	}
}
else
	displayAuth(csrf_create());