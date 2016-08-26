<?php

namespace DDev\Config;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Wrapper class for Symfony's configuration processor
 */
class ConfigProcessor
{
	function __construct($rootNodeName) {
		// Instantiate processor and tree builder
		$this->processor = new Processor();
		$this->tb = new TreeBuilder();

		// Create root node
		$root = $this->tb->root($rootNodeName);
		$this->root = $root;
	}

	public function process($config)
	{
		// Build the tree
		$tree = $this->tb->buildTree();

		// Process configuration
		$pconfig = $this->processor->process(
			$tree,
			$config
		);

		// Return processed configuration
		return $pconfig;
	}

	public function get_root()
	{
		return $this->root;
	}
}
