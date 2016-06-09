<?php
use Breadcrumbs\Breadcrumb;
/**
 * Breadcrumbs
 */
return [
	//Top level: Home
	Breadcrumb::make('Home', [], function (Breadcrumb $breadcrumb, array $params = []) {
		$breadcrumb->setTitle('Home');
	})->addChild(
		//Categories: Main -> Sub -> Product
		Breadcrumb::make('FrontendCategories', [], function (Breadcrumb $breadcrumb, array $params = []) {
			$breadcrumb->setTitle('Categories');
		})->addChild(
			Breadcrumb::make('SubcategoriesOverview', ['category_id'], function (Breadcrumb $breadcrumb, array $params) {
				//Set the title of the current category
				$category = new App\Category;
				$category = $category->find($params['category_id']);

				$breadcrumb->setTitle('Category : '. $category->retreive('name'));
			})->addChild(
				Breadcrumb::make('CategoryProducts', ['category_id', 'sub_category_id'], function (Breadcrumb $breadcrumb, array $params) {
					//Set the title of the current subcategory
					$category = new App\Category;
					$category = $category->find($params['sub_category_id']);

					$breadcrumb->setTitle('SubCategory : '. $category->retreive('name'));
				})->addChild(
					Breadcrumb::make('ProductItem', ['category_id', 'sub_category_id', 'product_id'], function (Breadcrumb $breadcrumb, array $params) {
						//Set the title of the current product
						$product = (new App\Product)->find($params['product_id']);

						$breadcrumb->setTitle('Product : '. $product->retreive('name'));
					})
				)
			)
		)
	)->addChild(
		//Cart
		Breadcrumb::make('CartOverview', [], function (Breadcrumb $breadcrumb, array $params) {
			$breadcrumb->setTitle('Winkelwagen');
		})->addChild(
			//Checkout
			Breadcrumb::make('CheckoutShow', [], function (Breadcrumb $breadcrumb, array $params) {
				$breadcrumb->setTitle('Betaalgegevens');
			})->addAlias('CheckoutPost')
		)
	)->addChild(
		//Search
		Breadcrumb::make('ProductSearch', [], function (Breadcrumb $breadcrumb, array $params) {
			$breadcrumb->setTitle('Zoeken');
		})
	)->addChild(
		//Login
		Breadcrumb::make('LoginGet', [], function (Breadcrumb $breadcrumb, array $params) {
			$breadcrumb->setTitle('Login');
		})->addAlias('LoginPost')
	)->addChild(
		//Login
		Breadcrumb::make('RegisterGet', [], function (Breadcrumb $breadcrumb, array $params) {
			$breadcrumb->setTitle('Registreer');
		})->addAlias('RegisterPost')
	)
];