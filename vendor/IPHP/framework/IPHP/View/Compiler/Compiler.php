<?php
namespace IPHP\View\Compiler;

use IPHP\View\ViewResponse;

class Compiler {
	private $viewPath;
	private $compiledPath;
	private $cachemapPath;

	public function __construct ($viewPath, $outputPath, $cachemapPath) {
		$this->viewPath 	= $viewPath;
		$this->compiledPath = $outputPath;
		$this->cachemapPath = $cachemapPath;
	}

	public function shouldCompile(ViewResponse $viewResponse) {
		$viewName = $this->getCompiledname($viewResponse);

		if (!file_exists($this->viewPath . $viewResponse->getViewPath()) || !file_exists($this->cachemapPath . $viewResponse->getViewPath())) {
			return true;
		}

		$cacheMap = include $this->cachemapPath . $viewResponse->getViewPath();
		
		foreach ($cacheMap as $path => $lastModTime) {
			if (!file_exists($path) || filemtime($path) != $lastModTime){
				return true;
			}	
		}
		
		return false;
	}

	public function getCompiledname (ViewResponse $viewResponse) {
		$path = $viewResponse->getViewPath();

		return str_replace(DIRECTORY_SEPARATOR, '.', $path);
	}

	public function compile (ViewResponse $viewResponse) {
		$currentView = new ViewParser($this->viewPath, $viewResponse->getViewPath());
		$masterView = $this->resolveParent($currentView);
		$this->resolveSections($masterView);
		$this->injectVarsIntoSections($currentView);

		$output = $this->injectPHP($masterView->getOutput());
		$name 	= $this->getCompiledname($viewResponse);
		
		return file_put_contents($this->cachemapPath . $name, $this->saveCacheMap($currentView)) &&
			   file_put_contents($this->compiledPath . $name, '<?php use IPHP\View\Helpers\VH;?>' . $output);

	}

	private function saveCacheMap (ViewParser $view) {
		$currentView = $view;
		$now 		 = time();
		$cacheMap = [];
		while ($currentView) {
			$path = $currentView->getPath();

			$time = filemtime($path);
			$cacheMap[$path] = $time;
			$currentView = $currentView->getParent();
		}
		$output = '';
		if  (!empty($cacheMap)) {
			$output = '<?php $map=[];' . chr(13);

			foreach ($cacheMap as $key => $value) {
				$output.= '$map["'. $key.'"] = '. $value .';'. chr(13);
			}

			$output.= 'return $map;';
		}

		return $output;
	}

	private function resolveParent(ViewParser $currentView): ViewParser {

		$currentView->parse();

		while ($currentView->getParent() != null) {
			$currentView = $currentView->getParent();
			$currentView->parse();
		}

		return $currentView;		
	}

	private function resolveSections(ViewParser $masterView) {
		$currentView = $masterView;

		while ($currentView != null) {
			$shows = $currentView->getShows();
			
			if (!empty($shows)) {
				foreach ($shows as $show) {
					$section = $this->resolveSection($show->getName(), $currentView);

					if ($section){
						$show->setSection($section);
					}
				}
			}

			$currentView = $currentView->getChild();
		}
	}

	private function resolveSection ($sectionName, ViewParser $masterView) {

		$currentView = $masterView;
		$currentSection = null;
		while($currentView != null) {

			if ($currentView->hasSection($sectionName)) {
				
				$section = $currentView->getSection($sectionName);
				
				if ($section->usesParent() && $currentSection != null){
					$currentSection = $section->setParent($currentSection);
				} 
				$currentSection = $section;
			}
			$currentView = $currentView->getChild();
		}

		return $currentSection;
	}

	private function injectVarsIntoSections (ViewParser $view) {
		$sections = $view->getSections();

		foreach ($sections as $section) {
			$uses = $section->getUses();

			if (count($uses) > 0) {
				$content = $section->getContent();

				if (in_array('all', $uses)) {
					$content = '<?php extract($__view->getInjectedVars()); ?>' . $content;
				} else {
					$varExtracts = '<?php ';
					foreach ($uses as $var) {
						$varExtracts.= 'if (!isset($'. $var . ') || $'. $var . ' != $__view->getInjectedVar("'. $var .'")){$'. $var . '=$__view->getInjectedVar("'. $var .'");}';
					}
					$varExtracts.=' ?>';

					$content = $varExtracts . $content;
				}

				
				$section->setContent($content);
			}
		}

		if ($view->getParent() != NULL){
			$this->injectVarsIntoSections($view->getParent());
		}
	}

	private function injectPHP ($input) {
		return preg_replace('/compiler\.print\((.*?)\)/', '<?php print($\1); ?>', $input);
	}
}