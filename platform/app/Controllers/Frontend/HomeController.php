<?php
namespace App\Controllers\Frontend;

use App\Controllers\Controller;

class HomeController extends Controller {
	public function index () {
		return $this->view("frontend::home.php");
	}

	public function about () {
		return $this->view("frontend::about.php");
	}

	public function policy () {
		return $this->view("frontend::policy.php");
	}

	public function contact () {
		return $this->view("frontend::contact.php");
	}
}