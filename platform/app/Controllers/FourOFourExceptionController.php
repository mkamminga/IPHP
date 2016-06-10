<?php
namespace App\Controllers;


class FourOFourExceptionController extends Controller {
	public function show () {
		$viewReposne =  $this->view("exceptions::four.o.four.php");

		$response = $viewReposne->getHttpResponse();

		$response->setStatusCode(404);

		return $viewReposne;
	}
}