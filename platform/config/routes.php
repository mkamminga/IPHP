<?php
use IPHP\Http\Routing\Route;
use IPHP\Http\Routing\RouteCollection;

return [
	'settings' => [
		'defaults' => [
			'[id]' => '(?<id>[0-9]+)',
			'[title]' => '(?<title>[A-z\_0-9\-]+)'
		],
		'exceptions' => [
			404 => App\Controllers\HomeController::class,
			403 => App\Controllers\HomeController::class 
		]
	],
	'routes' => [
		new Route('Home', 'home', 'get', App\Controllers\HomeController::class, 'showHome'),
		new RouteCollection('/subdir/[id]/[title]/', [App\Filters\UserLoggedinFilter::class => ["group" => "user"]], [
			(new Route('Home', '/home', 'get', App\Controllers\HomeController::class, 'showHome'))->addCollection([
				new Route('Home', '/home', 'get', App\Controllers\HomeController::class, 'showHome')
			])
		])
	]
];