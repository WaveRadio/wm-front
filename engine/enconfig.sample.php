<?php

// Fill in the config params and rename me to enconfig.php

// Database
define ('DB_HOST', 'localhost');
define ('DB_NAME', 'wmbase');
define ('DB_USER', 'waveman');
define ('DB_PASSWORD', '_change_me_plz');

// System settings
define ('ADMIN_HASH_SALT', '_change_me_with_pure_randomness_');
define ('BROADCAST_HASH_SALT', '_change_me_with_pure_randomness_');
define ('WM_DATA_DIR', '/opt/wavemanager/share'); // Place where WM Core reads and writes its data

// WaveManager Core Backend
define ('WM_CORE_BACKEND_ADDRESS', 'backend.waveradio.org');
define ('WM_CORE_BACKEND_PORT', 8903); // 8903 is default
define ('WM_CORE_BACKEND_SECURE', false);

// Public API
define ('API_HISTORY_MAX', 10);
define ('API_HISTORY_DEFAULT', 10);
define ('API_ALLOW_CORS', true); // allow other websites to use our public API

// Define your TuneIn Partner credentials here
// Place your station tag in place of 'wavestation'
// Define as much stations as you want (undefined stations will be dropped)
// See https://tunein.com/broadcasters/api/
$tunein_credentials = Array(
	'wavestation' => Array(
		'partner_id'  => 'PaRtNeRiD32',
		'partner_key' => 'PaRtNeRkEy8',
		'station_id'  => 's31337228'
	)
);


// This is configured automatically
define ('WORK_PATH', dirname(__FILE__));
