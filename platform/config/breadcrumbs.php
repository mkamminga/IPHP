<?php
use Breadcrumbs\Breadcrumb;
/**
 * Breadcrumbs
 */
return [
	Breadcrumb::make('Home', [], function (Breadcrumb $breadcrumb, array $params = []) {
		$breadcrumb->setTitle('Home');
	})->setChild(
		Breadcrumb::make('Categories', [], function (Breadcrumb $breadcrumb, array $params = []) {
			$breadcrumb->setTitle('Categories');
		})->setChild(
			Breadcrumb::make('CategoryProducts', ['subcategory'], function (Breadcrumb $breadcrumb, array $params = []) {
				//Set the title of the current category
				$category = new App\Category;
				$category = $category->find($params['subcategory']);

				$breadcrumb->setTitle('Category : '. $category->retreive('name'));
			})
		)
	);
];