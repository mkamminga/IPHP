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
			404 => App\Controllers\HomeController::class,
			403 => App\Controllers\HomeController::class 
		]
	],
	'routes' => [
		new Route('HomeA', 'home', 'get', App\Controllers\HomeController::class, 'showHome'),
		new RouteCollection('/subdir/[num:id]/[alpha_num:title]/', [App\Filters\UserLoggedinFilter::class => ["group" => "user"]], [
			(new Route('HomeB', '/home', 'get', App\Controllers\HomeController::class, 'showHome'))->addCollection([
				new Route('HomeC', '/home', 'get', App\Controllers\HomeController::class, 'showHome')
			])
		])
	]
];