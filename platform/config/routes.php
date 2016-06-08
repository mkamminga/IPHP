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
		new RouteCollection('', [App\Filters\CartFilter::class => [], App\Filters\RegisterViewComposerFilter::class => []], [
			new Route('Home','/home?', 'get', App\Controllers\Frontend\HomeController::class, 'index'),
			//categories/subcategories/product
			(new Route('FrontendCategories','/categories', 'get', App\Controllers\Frontend\CategoriesController::class, 'showMainCategories'))->addCollection([
				//Subcategories
				(new Route('SubcategoriesOverview','/[num:category_id]/categories', 'get', App\Controllers\Frontend\CategoriesController::class, 'showSubcategories'))->addCollection([
					//subcategiry products overview
					(new Route('CategoryProducts', '/[num:sub_category_id]/products', 'get', App\Controllers\Frontend\ProductsController::class, 'showProducts'))->addCollection([
						//product
						new Route('ProductItem', '/[num:product_id]', 'get', App\Controllers\Frontend\ProductsController::class, 'showProduct')
					])
				])
			]),
			//cart - ajax actions
			new Route('CartShowAjaxCart', '/cart-show-mini', 'get', App\Controllers\Frontend\ShoppingCartController::class, 'shoppingCart'),
			new Route('CartItemPost', '/cart-add', 'post', App\Controllers\Frontend\ShoppingCartController::class, 'addProductToCart'),
			new Route('CartItemQuantityUpdate', '/cart-update-quantity', 'post', App\Controllers\Frontend\ShoppingCartController::class, 'updateItemQuantity'),
			new Route('CartItemRemove', '/cart-remove-item', 'post', App\Controllers\Frontend\ShoppingCartController::class, 'removeItem'),
			
			new RouteCollection('/bestelling-afronden', [], [
				new Route('CartOverview', '/winkelwagen', 'get', App\Controllers\Frontend\ShoppingCartController::class, 'showCart'),
				//checkout
				new Route('CheckoutShow', '/bestelgegevens', 'get', App\Controllers\Frontend\OrdersController::class, 'showCheckout'),
				new Route('CheckoutPost', '/bestelgegevens', 'post', App\Controllers\Frontend\OrdersController::class, 'postCheckout'),
				new Route('CheckoutStatus', '/status', 'get', App\Controllers\Frontend\OrdersController::class, 'showStatus')
			]),
			//About,
			new Route('About', '/about', 'get', App\Controllers\Frontend\HomeController::class, 'index'),
			//Policy
			new Route('Policy', '/policy', 'get', App\Controllers\Frontend\PolicyController::class, 'index'),
			//Contact
			new Route('Contact', '/contact', 'get', App\Controllers\Frontend\ContactController::class, 'index'),
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