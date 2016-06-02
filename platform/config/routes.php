<?php
use IPHP\Http\Routing\Route;
use IPHP\Http\Routing\RouteCollection;

function routeResource ($name, $class) {
	return [
		new Route($name . 'Overview', '/', 'get', $class, 'overview'),
		new Route($name . 'ShowAdd', '/add', 'get', $class, 'showAdd'),
		new Route($name . 'Post', '/add', 'post', $class, 'post'),
		new Route($name . 'ShowEdit', '/edit/[num:id]', 'get', $class, 'showEdit'),
		new Route($name . 'Put', '/edit/[num:id]', 'post', $class, 'put'),
		new Route($name . 'ShowDelete', '/delete/[num:id]', 'get', $class, 'showDelete'),
		new Route($name . 'Delete', '/delete/[num:id]', 'post', $class, 'delete')
	];
}

function add (array $data, Route $route) {
	$data[] = $route;
	
	return $data;
}

return [
	'settings' => [
		'defaults' => [
			'[num]' => '-?[0-9]+',
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
				new RouteCollection('/navigations', [], routeResource('Navigation', App\Controllers\Backend\NavigationController::class)),
				//Categories
				new RouteCollection('/categories', [], routeResource('Categories', App\Controllers\Backend\CategoriesController::class)),
				//Products
				new RouteCollection('/products', [], add(
					routeResource('Products', App\Controllers\Backend\ProductsController::class),
					new Route('ProductsSubCategories', '/category/[num:id]/subcategories', 'get', App\Controllers\Backend\ProductsController::class, 'ajaxSubcategories')
				)),
				
			])
		])
	]
];