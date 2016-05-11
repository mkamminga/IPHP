<?php
use IPHP\Http\Routing\Route;
use IPHP\Http\Routing\RouteResource;
use IPHP\Http\Routing\RouteCollection;

return [
	'settings' => [
		'defaults' => [
			'[id]' => '(?<id>[0-9]+)',
			'[parentid]' => '(?<parentid>[0-9]+)',
			'[title]' => '(?<title>[A-z\_0-9\-]+)'
		],
		'exceptions' => [
			404 => '404 handler',
			403 => '403 handler' 
		]
	],
	'routes' => [
		new Route('Home', 'home/', 'get', App\Controllers\HomeController::class, 'showHome'),
		new RouteCollection('/subdir/[id]/[title]/', [App\Filters\UserLoggedinFilter::class => ["group" => "user"]], [
			new Route('Home', '/home', 'get', App\Controllers\HomeController::class, 'showHome')
		])
	]
];