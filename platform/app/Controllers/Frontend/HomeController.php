<?php
namespace App\Controllers\Frontend;

use App\Controllers\Controller;

class HomeController extends Controller {
	public function index () {
		return $this->view("frontend::home.php");
	}

}