<?php
namespace App\Composers;

use IPHP\App\ServiceManager;
use Breadcrumbs\BreadcrumbResolver;
use App\Navigation;
use App\Category;

class FrontendViewComposer extends MainViewComposer {
	public function resolveBreadCrumbs () {
		//inject the breadcrumb in the viewreponse, if found 
		if ($this->sm->hasService('viewResponse')) {
			$viewResponse 	= $this->sm->getService('viewResponse');
			$router 		= $this->sm->getService('router');

			$breadCrumbs 	= include config_path . DIRECTORY_SEPARATOR .  'breadcrumbs.php';
			$resolver 		= new BreadcrumbResolver($router, $breadCrumbs);

			$resolver->compile($router->getRouteMatch());
			$viewResponse->setVar('breadcrumbs', $resolver->getCompiled());
		}
	}

	public function resolveMenu () {
		if ($this->sm->hasService('viewResponse')) {
			$viewResponse 	= $this->sm->getService('viewResponse');
			$navigation 	= new Navigation;
			$menu 			= $navigation->getCollection($navigation->allSorted());
			//categories
			$category 					= new Category;
			
			$menuCategories 			= new \stdClass;
			$menuCategories->link 		= "FrontendCategories";
			$menuCategories->name 		= "Categorieën";
			$menuCategories->subMenu 	= [];
			
			$relationSubCategories = $category->relatedSubCategories();
			$category->byName($relationSubCategories->select());
			
			$categories 			= $category->get(
				$category->byName(
					$category->withRelated($relationSubCategories)->allParent()
				)
			);

			foreach ($categories as $categoryItem) {
				$menuItem 			= new \stdClass;
				$menuItem->link 	= 'SubcategoriesOverview';
				$menuItem->params 	= ['category_id' => $categoryItem->retreive('id')];
				$menuItem->name 	= $categoryItem->retreive('name');
					
				$subCategories = $categoryItem->getRelated('subCategories');
				if (count($subCategories) > 0) {
					$menuItem->subMenu = [];
					foreach ($subCategories as $subCategory){
						$subMenuItem 			= new \stdClass;
						$subMenuItem->link 		= 'CategoryProducts';
						$subMenuItem->params 	= array_merge($menuItem->params, ['sub_category_id' => $subCategory->retreive('id')]);
						$subMenuItem->name 		= $subCategory->retreive('name');
						
						$menuItem->subMenu[] 	= $subMenuItem;
					}
				}
				
				$menuCategories->subMenu[] = $menuItem;
			}
			
						
			$menu[] 				= $menuCategories;
			$viewResponse->setVar('menus', $menu);
		}
	}
	/**
	 * @override
	 */
	public function compose () {
		parent::compose();

		$this->resolveBreadCrumbs();
		$this->resolveMenu();
	}
}