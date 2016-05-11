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
	public function __construct (ServiceManager $serviceManager) {
		$this->sm = $serviceManager;
	}

	public function showHome (Request $request, $id = 0, $title = "", FirstModel $model, OrderRows $or) {
		var_dump($or->find(22,12)->delete());
		$data = $model->find(9);

		return new ViewResponse("compilethis.php", ["data" => $data->contents()]);
	}
}