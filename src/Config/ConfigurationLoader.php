<?php

namespace DDev\Config;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationLoader
{
	public function load($path, $configuration)
	{
		$config = Yaml::parse($resource);

		$processor = new Processor();
		$pconfig = $processor->processConfiguration(
			$configuration,
			$config
		);

		return $pconfig;
	}
}
