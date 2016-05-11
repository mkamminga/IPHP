<?php
namespace IPHP\View\Compiler;

use IPHP\View\ViewResponse;

class Compiler {
	private $viewPath;
	private $compiledPath;

	public function __construct ($viewPath, $outputPath) {
		$this->viewPath = $viewPath;
		$this->compiledPath = $outputPath;
	}

	public function shouldCompile(ViewResponse $viewResponse) {
		return !file_exists($this->getCompiledname($viewResponse));
	}

	public function getCompiledname (ViewResponse $viewResponse) {
		$path = $viewResponse->getViewPath();

		return basename($path);
	}

	public function compile (ViewResponse $viewResponse) {
		$currentView = new ViewParser($this->viewPath, $viewResponse->getViewPath());
		$masterView = $this->resolveParent($currentView);
		$this->resolveSections($masterView);
		$this->injectVarsIntoSections($currentView);
		$output = $this->injectPHP($masterView->getOutput());

		return file_put_contents($this->compiledPath . $this->getCompiledname($viewResponse), $output);

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
						$varExtracts.= '$'. $var . '=$__view->getInjectedVar("'. $var .'");';
					}
					$varExtracts.=' ?>';

					$content = $varExtracts . $content;
				}

				
				$section->setContent($content);
			}
		}
	}

	private function injectPHP ($input) {
		return preg_replace('/compiler\.print\((.*?)\)/', '<?php print($\1); ?>', $input);
	}
}