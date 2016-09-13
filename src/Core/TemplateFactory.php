<?php

namespace DDev\Core;

class TemplateFactor implements ArrayAccess {
	function __construct() {
		$this->props = array();
	}
	function offsetSet($key, $value) {
		if (is_null($key)) {
			// Do nothing - property values should not
			// be appended with an arbitrary key
			return;
		}
		$this->props[$key] = $value;
	}
	function offsetExists($key) {
		return isset($this->props[$key]);
	}
	function offsetUnset($key) {
		unset($this->props[$key]);
	}
	function offsetGet($key) {
		return $this->props[$key];
	}

	function set_webpath($value) {
		$this->webpath = $value;
	}
	function make_tmpl($filepath) {
		$tmpl = new Template();
		$tmpl->set_template_file($filepath);

		// Add template properties
		foreach ($this->props as $key => $value) {
			$tmpl->$key = $value;
		}
		
		return $tmpl;
	}
}
