<?php
use IPHP\Http\Routing\Route;
use IPHP\Http\Routing\RouteCollection;

return [
	'settings' => [
		'defaults' => [
			'[num]' => '[0-9]+',
			'[alpha_num]' => '[A-z\_0-9\-]+'
		],
		'exceptions' => [
			404 => new Route('Route404', '', 'all', App\Controllers\Exceptions\FourOFourException::class, 'show')
		]
	],
	'routes' => [
		new Route('LoginGet','/login', 'get', App\Controllers\LoginController::class, 'showLogin'),
		new Route('LoginPost','/login', 'post', App\Controllers\LoginController::class, 'postLogin')
	]
];