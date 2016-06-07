<?php
use Breadcrumbs\Breadcrumb;
/**
 * Breadcrumbs
 */
return [
	Breadcrumb::make('Home', [], function (Breadcrumb $breadcrumb, array $params = []) {
		$breadcrumb->setTitle('Home');
	})->setChild(
		Breadcrumb::make('FrontendCategories', [], function (Breadcrumb $breadcrumb, array $params = []) {
			$breadcrumb->setTitle('Categories');
		})->setChild(
			Breadcrumb::make('SubcategoriesOverview', ['category_id'], function (Breadcrumb $breadcrumb, array $params) {
				//Set the title of the current category
				$category = new App\Category;
				$category = $category->find($params['category_id']);

				$breadcrumb->setTitle('Category : '. $category->retreive('name'));
			})->setChild(
				Breadcrumb::make('CategoryProducts', ['category_id', 'sub_category_id'], function (Breadcrumb $breadcrumb, array $params) {
					//Set the title of the current subcategory
					$category = new App\Category;
					$category = $category->find($params['sub_category_id']);

					$breadcrumb->setTitle('SubCategory : '. $category->retreive('name'));
				})->setChild(
					Breadcrumb::make('ProductItem', ['category_id', 'sub_category_id', 'product_id'], function (Breadcrumb $breadcrumb, array $params) {
						//Set the title of the current product
						$product = (new App\Product)->find($params['product_id']);

						$breadcrumb->setTitle('Product : '. $product->retreive('name'));
					})
				)
			)
		)
	)
];