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
		new RouteCollection('', [App\Filters\RegisterViewComposerFilter::class => []], [
			new Route('Home','/home?', 'get', App\Controllers\Frontend\HomeController::class, 'index'),
			(new Route('Categories','/categories', 'get', App\Controllers\Frontend\CategoriesController::class, 'showSubcategories'))->addCollection([
				new Route('CategoryProducts', '/[num:subcategory]/products', 'get', App\Controllers\Frontend\CategoriesController::class, 'showProducts')
			]),
			//Login
			new Route('LoginGet','/login', 'get', App\Controllers\Frontend\LoginController::class, 'showLogin'),
			new Route('LoginPost','/login', 'post', App\Controllers\Frontend\LoginController::class, 'postLogin'),
			new Route('Logout','/logout', 'get', App\Controllers\Frontend\LoginController::class, 'logout')
		])
	]
];