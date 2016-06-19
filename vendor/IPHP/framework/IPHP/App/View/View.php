<?php
namespace IPHP\App\View;

use IPHP\App\ServiceManager;
use IPHP\Http\Response;
use IPHP\View\Compiler\Compiler;
use IPHP\View\ViewResponse;

class View {
	private $viewResponse;
	private $compiler;
	private $serviceManager;
	private $allowedApps = [];

	public function __construct (ViewResponse $viewResponse, Compiler $compiler, ServiceManager $serviceManager) {
		$this->viewResponse = $viewResponse;
		$this->compiler = $compiler;
		$this->serviceManager = $serviceManager;

		if ($serviceManager->hasConfig('providers')){
			$this->allowedApps = array_keys($serviceManager->getConfig('providers')->data());
		}
	}	

	public function getCompiledOutput() {
		try {
			//throws invalid config exception
			if ($this->compiler->shouldCompile($this->viewResponse) && !$this->compiler->compile($this->viewResponse)) {
				throw new \Exception("Couldn't compile view reponse!");
			}

			return $this->captureViewOutput($this->compiler->getCompiledPath() . $this->compiler->getCompiledname($this->viewResponse));
			
		} catch (\Exception $e) {
			print($e->getMessage());
			exit;
		}
	}

	private function captureViewOutput ($compiledFile) {
		try {
			ob_start();
			$__view = $this->viewResponse;
			require $compiledFile;

			$data = ob_get_contents();
			ob_end_clean();

			return $data;
		} catch (\Exception $e) {
			ob_end_clean();
			
			throw new \Exception("View rendering exception: ". $e->getMessage() );
		}
	}

	public function service ($name = '') {
		if (in_array($name, $this->allowedApps)){
			return $this->serviceManager->getService($name);
		}

		return NULL;
	}

	public function render () {
		$httpResponse = $this->viewResponse->getHttpResponse();
		$httpResponse->setBody($this->getCompiledOutput());
		$httpResponse->send();
	}
}