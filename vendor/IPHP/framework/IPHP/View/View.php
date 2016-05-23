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
		$this->compiler = new Compiler($paths['path'], $paths['compiled_path'], $paths['cache_map']);
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
		return showView($this->viewResponse, $compiledFile);
	}

	public function render () {
		$httpResponse = $this->viewResponse->getHttpResponse();
		$httpResponse->setBody($this->getCompiledOutput());
		$httpResponse->send();
	}
}
/**
 * extract data and prevent the calling of this
 * @param  ViewResponse $view         [description]
 * @param  [type]       $compiledFile [description]
 * @return [type]                     [description]
 */
function showView (ViewResponse $view, $compiledFile) {
	try {
		ob_start();
		$__view = $view;
		require $compiledFile;

		$data = ob_get_contents();
		ob_end_clean();

		return $data;
	} catch (\Exception $e) {
		ob_end_clean();
		throw new \Exception("View rendering exception: ". $e->getMessage());
		
	}
}