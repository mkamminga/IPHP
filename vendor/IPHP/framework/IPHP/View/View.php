<?php
namespace IPHP\View;

use IPHP\App\ServiceManager;
use IPHP\Http\Response;
use IPHP\View\Compiler\Compiler;
use IPHP\View\Helpers\VH;

class View {
	private $viewResponse;
	private $compiler;
	private $paths = [];
	private $serviceManager;

	public function __construct (ViewResponse $viewResponse, array $paths = [], ServiceManager $serviceManager) {
		$this->paths = $paths;
		$this->viewResponse = $viewResponse;
		$this->compiler = new Compiler($paths['path'], $paths['compiled_path'], $paths['cache_map']);
		$this->serviceManager = $serviceManager;
	}	

	public function getCompiledOutput() {
		try {
			//throws invalid config exception
			if ($this->compiler->shouldCompile($this->viewResponse) && !$this->compiler->compile($this->viewResponse)) {
				throw new \Exception("Couldn't compile view reponse!");
			}

			return $this->captureViewOutput($this->paths['compiled_path'] . $this->compiler->getCompiledname($this->viewResponse));
			
		} catch (\Exception $e) {
			print($e->getMessage());
			exit;
		}
	}

	private function captureViewOutput ($compiledFile) {
		try {
			ob_start();
			$__view = $this;
			require $compiledFile;

			$data = ob_get_contents();
			ob_end_clean();

			return $data;
		} catch (\Exception $e) {
			ob_end_clean();
			var_dump($e);
			throw new \Exception("View rendering exception: ". $e->getMessage() );
			
		}
	}

	public function service ($name = '') {
		return $this->serviceManager->getService($name);
	}

	public function render () {
		$httpResponse = $this->viewResponse->getHttpResponse();
		$httpResponse->setBody($this->getCompiledOutput());
		$httpResponse->send();
	}
}