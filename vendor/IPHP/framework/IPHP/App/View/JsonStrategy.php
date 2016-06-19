<?php
namespace IPHP\App\View;

use IPHP\View\JsonResponse as ViewResponse;

class JsonStrategy { 
    public function resolve (ViewResponse $vr) {
        $vars = $vr->getInjectedVars();
        
        $output = json_encode($vars);
        $httpResponse = $vr->getHttpResponse();
        $httpResponse->addHeader('Content-Type', 'application/json');
        $httpResponse->setBody($output);
		$httpResponse->send();
    }
}