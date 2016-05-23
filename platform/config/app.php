<?php

return [
	'views' => [
		'path' 			=> dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR,
		'compiled_path' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR,
		'cache_map' 	=>  dirname(__DIR__) . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'maps' . DIRECTORY_SEPARATOR
	],
	'database' => [
		'default' => [
			'engine' 	=> 'mysql',
			'host' 		=> 'localhost',
			'dbname' 	=> 'awrlacle_db2',
			'dbuser'	=> 'root',
			'dbpassword' => 'root'
		]
	]
];