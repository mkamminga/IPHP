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
		//Frontend
		new RouteCollection('', [App\Filters\RegisterViewComposerFilter::class => []], [
			new Route('Home','/home?', 'get', App\Controllers\Frontend\HomeController::class, 'index'),
			(new Route('Categories','/categories', 'get', App\Controllers\Frontend\CategoriesController::class, 'showSubcategories'))->addCollection([
				new Route('CategoryProducts', '/[num:subcategory]/products', 'get', App\Controllers\Frontend\CategoriesController::class, 'showProducts')
			]),
			//Login
			new Route('LoginGet','/login', 'get', App\Controllers\Frontend\LoginController::class, 'showLogin'),
			new Route('LoginPost','/login', 'post', App\Controllers\Frontend\LoginController::class, 'postLogin'),
			new Route('Logout','/logout', 'get', App\Controllers\Frontend\LoginController::class, 'logout')
		]),

		//CMS
		new RouteCollection('/beheer', [], [
			new Route('CmsLogin', '/login?', 'get', App\Controllers\Backend\LoginController::class, 'showLogin'),
			new Route('CmsLogin', '/login?', 'post', App\Controllers\Backend\LoginController::class, 'postLogin'),

			new RouteCollection('', [App\Filters\RegisterMainViewComposerFilter::class => [], App\Filters\UserLoggedinFilter::class => ['group' => 'cmsadin', 'route' => 'Home', 'params' => []]], [

				new Route('Dashboard', '/dashboard', 'get', App\Controllers\Backend\DashboardController::class, 'index'),
				//navivation
				new RouteCollection('/navigations', [], [
					new Route('NavigationOverview', '/', 'get', App\Controllers\Backend\NavigationController::class, 'overview'),
					new Route('NavigationShowAdd', '/add', 'get', App\Controllers\Backend\NavigationController::class, 'showAdd'),
					new Route('NavigationPost', '/add', 'post', App\Controllers\Backend\NavigationController::class, 'post'),
					new Route('NavigationShowEdit', '/edit/[num:id]', 'get', App\Controllers\Backend\NavigationController::class, 'showEdit'),
					new Route('NavigationPut', '/edit/[num:id]', 'post', App\Controllers\Backend\NavigationController::class, 'put'),
					new Route('NavigationShowDelete', '/delete/[num:id]', 'get', App\Controllers\Backend\NavigationController::class, 'showDelete'),
					new Route('NavigationDelete', '/delete/[num:id]', 'post', App\Controllers\Backend\NavigationController::class, 'delete')
				]),
				//categories
				new RouteCollection('/categories', [], [
				])
			])
		])
	]
];