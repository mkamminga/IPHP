<?php
namespace App\Controllers;
use IPHP\App\ServiceManager;
use IPHP\Http\Request;
use IPHP\View\ViewResponse;
use App\Category;
use App\FirstModel;
use App\OrderRows;

class HomeController {
	private $sm;
	private $lastId;
	
	public function __construct (ServiceManager $serviceManager) {
		$this->sm = $serviceManager;
	}

	public function showHome (Request $request, $id = 0, $title = "", FirstModel $model) {
		/*$this->testCategorySave();
		$this->testCategoryUpdate();
		$this->testDelete();*/
		$data = [];
		//$data = $model->with('user')->get($model->all());
		return new ViewResponse("compilethis.php", ["data" => [$data]]);
	}

	public function testCategorySave () {
		$category = new Category;
		$category->set('name', 'New and new');
		var_dump($category->save());

		$this->lastId = $category->retreive('id');
	}

	public function testCategoryUpdate () {
		$category = (new Category)->find(3);
		$category->set('name', $category->retreive('name') . 'New and new');
		var_dump($category->save());
	}

	public function testDelete () {
		if ($this->lastId >= 0) {
			$category = (new Category)->find($this->lastId);
			var_dump($category->delete());
		} else {
			var_dump("Failed execution of test");
		}
	}

}