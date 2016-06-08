<?php
namespace IPHP\View\Compiler;
use IPHP\View\Compiler\ViewComponents\Section;
use IPHP\View\Compiler\ViewComponents\SingleLineSection;
use IPHP\View\Compiler\ViewComponents\Show;

class ViewParser extends AbstractShowParser {
	private $fileName;
	private $parentView = NULL;
	private $childView = NULL;
	private $sections = [];
	private $partials = [];
	private $output = NULL;
	private $basePath;

	public function __construct($baseViewPath, $fileName, ViewParser $childView = null){
		$this->basePath = $baseViewPath;
		$this->fileName = $fileName;

		if (!file_exists($baseViewPath . $fileName)) {
			throw new \Exception("Couldn't find file: ". $fileName, 1);
		}
		$this->childView = $childView;
	}

	public function getPath () {
		return $this->basePath . $this->fileName;
	}

	private function extractParent ($data) {
		return preg_replace_callback('/[\n\r]*>>\s+parent\(\'([a-z\.\:\:]+)\'\)[\n\r]*/', function ($matches) {
			$this->parentView = new ViewParser($this->basePath, str_replace("::", DIRECTORY_SEPARATOR, $matches[1]), $this);

			return '[parent]';
		}, $data);
	}

	public function getParent() {
		return $this->parentView;
	}

	public function getChild () {
		return $this->childView;
	}

	private function extractPartials ($data) {
		$data =  preg_replace_callback('/[\n\r]*>>\s+partial\(\'([a-zA-Z0-9\:\.]+)\'\)+/s', function ($matches) {
			$path = $this->basePath . str_replace("::", DIRECTORY_SEPARATOR, $matches[1]);

			if (file_exists($path)) {
				$this->partials[] = $path;

				return file_get_contents($path);
			}

			return "<< File '". $path ."' not found >>";
		}, $data);

		return $data;
	}

	public function getPartials () {
		return $this->partials;
	}

	private function extractSections ($data) {
		$data =  preg_replace_callback('/[\n\r]*>>\s+section\(\'([a-z]+)\'\,\s*\'(.*)\'\)[\n\r]*/', function ($matches) {
			$this->sections[$matches[1]] = new SingleLineSection($matches[1], $matches[2]);
			
			return "";
		}, $data);

		$data =  preg_replace_callback('/[\n\r]*>>\s+section\(\'([a-z]+)\'\)(.+)<<\s+section\(\'\1\'\)[\n\r]*/s', function ($matches) {
			$this->sections[$matches[1]] = new Section($matches[1], $matches[2]);
			
			return "";
		}, $data);

		return $data;
	}

	public function hasSection ($name = '') {
		return array_key_exists($name, $this->sections);
	}

	public function getSection ($name = '') {
		return array_key_exists($name, $this->sections) ? $this->sections[$name] : NULL;
	}

	public function getShows () {
		$shows =  $this->shows;

		foreach ($this->sections as $section) {
			$sectionShows = $section->getShows();

			foreach ($sectionShows as $show) {
				$shows[] = $show;
			}
		}

		return $shows;
	}

	public function getSections () {
		return $this->sections;
	}

	public function getOutput () {
		return $this->parseShows($this->output);
	}

	public function parse() {
		$data = '';
		if (file_exists($this->basePath . $this->fileName)) {
			$data = file_get_contents($this->basePath . $this->fileName);
			$data = $this->extractPartials($data);
			$data = $this->extractParent($data);
			$data = $this->extractSections($data);
			$data = $this->extractShows($data);

			$this->output = $data;
		}
	}
}