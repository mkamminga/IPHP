<?php
use Breadcrumbs\Breadcrumb;
/**
 * Breadcrumbs
 */
return [
	Breadcrumb::make('HomeA', [], function (Breadcrumb $breadcrumb, array $params = []) {
		$breadcrumb->setTitle('Base');
	})->setChild(Breadcrumb::make('HomeB', ['id', 'title'], function (Breadcrumb $breadcrumb, array $params = []) {
			$breadcrumb->setTitle('Title');
		})->setChild(
			Breadcrumb::make('HomeC', ['id', 'title'], function (Breadcrumb $breadcrumb, array $params = []) {
				$breadcrumb->setTitle('Id : '. $params['id']);
			})
		)
	)
];