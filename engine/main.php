<?php

$content = Array(
	'backend' => Array(
		'address'    => WM_CORE_BACKEND_ADDRESS,
		'port'       => WM_CORE_BACKEND_PORT,
		'secure'     => WM_CORE_BACKEND_SECURE,
		'passphrase' => setNewBackendPassphrase()
	),

	'csrf_token' => csrf_create()
);

display('panel', $content);