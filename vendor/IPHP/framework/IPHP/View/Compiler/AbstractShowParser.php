<?php
namespace IPHP\View\Compiler;

use IPHP\View\Compiler\ViewComponents\Show;

abstract class AbstractShowParser {
	protected $shows = [];

	protected function extractShows ($data) {
		return preg_replace_callback('/[\n\r]*<<\s+show\(\'([a-z]+)\'\,\s*\'([a-zA-Z\s]*)\'\)[\n\r]*/', function ($matches) {
			$this->shows[$matches[1]] = new Show($matches[1], $matches[2]);

			return '[show='. $matches[1] .']';
		}, $data);	
	}

	protected function parseShows ($output) {
		foreach ($this->shows as $show) {
			$output = preg_replace('/[\n\r]*\[show\='. $show->getName() .'\][\n\r]*/', $show->getOutput(), $output);
		}

		return $output;
	}
}