<?php
namespace IPHP\View;

use IPHP\Http\Response;
use IPHP\View\Compiler\Compiler;

class View {
	private $viewResponse;
	private $compiler;
	private $paths = [];

	public function __construct (ViewResponse $viewResponse, array $paths = []) {
		$this->paths = $paths;
		$this->viewResponse = $viewResponse;
		$this->compiler = new Compiler($paths['path'], $paths['compiled_path']);
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
			$__view = $this->viewResponse;
			require $compiledFile;

			$data = ob_get_contents();
			ob_end_clean();
		} catch (\Exception $e) {
			ob_end_clean();
			throw new \Exception("View rendering exception: ". $e->getMessage());
			
		}

		return $data;
	}

	public function render () {
		$httpResponse = $this->viewResponse->getHttpResponse();
		$httpResponse->setBody($this->getCompiledOutput());
		$httpResponse->send();
	}
}