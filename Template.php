<?php

namespace DDev;

class Template {
	private $vars = array();
	private $templateFile = '';

	function __set($index, $value) {
		$this->vars[$index] = $value;
	}
	function set_template_file($templateFile) {
		$this->templateFile = $templateFile;
	}
	function loadInclude($file) {
		foreach ($this->vars as $key => $value) $$key = $value;
		require($file);
	}
	function run() {
		$this->loadInclude($this->templateFile);
	}
}
