<?php
namespace IPHP\View\Compiler\ViewComponents;

class SingleLineSection extends Section {
	protected function parse () {
		parent::parse();

		$this->content = preg_replace('/\s*{{\s*(.*?)\s*}}/', 'compiler.print(\1)', $this->content);
	}
}