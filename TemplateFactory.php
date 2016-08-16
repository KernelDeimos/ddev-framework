<?php

namespace DDev;

class TemplateFactory {
	function make_tmpl($filepath) {
		$tmpl = new Template();
		$tmpl->set_template_file($filepath);
		return $tmpl;
	}
}
