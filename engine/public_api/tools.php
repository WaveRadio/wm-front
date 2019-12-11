<?php

$content = array(
	'stations' => db_getStations(),
	'amounts'  => [10, 20, 50, 100]
);

display('public_tools', $content);