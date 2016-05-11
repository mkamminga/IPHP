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
		$data = $model->with('user')->find(1);
		return new ViewResponse("compilethis.php", ["data" => [$data->contents(), $data->getRelated('user')]]);
	}
}