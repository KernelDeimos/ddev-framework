<?php

namespace DDev\Core;

class TemplateFactory {
	function make_tmpl($filepath) {
		$tmpl = new Template();
		$tmpl->set_template_file($filepath);
		return $tmpl;
	}
}
