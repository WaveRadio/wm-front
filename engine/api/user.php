<?php

function processChangePassword() {
	$password = trim($_POST['password']);
	$username = strtolower($_SESSION['user_login']);

	if (empty($username)) {
		json_respond(-1, 'Internal error, report a bug plz');
	}

	if (empty($password)) {
		json_respond(1, 'No password specified');
	}

	if (strlen($password) > USER_MAX_PASSWORD_LENGTH) {
		json_respond(2, 'Unbelievable, password is too long!');
	}

	$hashedPassword = enhashPassword($_SESSION['user_login'], $password, 'admin');
	db_setAdminUserHash($_SESSION['user_login'], $hashedPassword);
	json_respond (0, 'OK');
}

array_shift($route); // remove /user/
switch ($route[0])
{
	case 'setpassword' :
		processChangePassword();
		break;

	default:
		json_respond (-1, 'Bad API request');
}