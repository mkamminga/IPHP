<?php
namespace App\Controllers\Frontend;

use IPHP\View\ViewResponse;

class HomeController {
	public function index () {
		return new ViewResponse("frontend/home.php");
	}

}