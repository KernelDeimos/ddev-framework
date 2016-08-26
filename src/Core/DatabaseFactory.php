<?php

namespace DDev\Core;

use \DDev\Config\ConfigProcessor

/**
 * This class acts as a factory for all database objects.
 */
class DatabaseFactory {

	// Store a DBConnectionManager
	private $connection_manager;

	private function process_config($config)
	{
		// Instantiate the processor
		$processor = new ConfigProcessor('database_factory');

		// Blueprint the tree
		$processor->get_root()
			->children()
				->scalarNode('name')->end()
				->scalarNode('driver')->end()
					->defaultValue('mysql')
				->end()
				->scalarNode('host')
					->defaultValue('localhost')
				->end()
				->scalarNode('user')->end()
				->scalarNode('pass')->end()
				->scalarNode('schema')->end()
			->end()

		// Process configuration
		$this->config = $processor->process($config);
	}

	/**
	 * @param  config  map containing configuration values
	 */
	function __construct($config) {
		$this->process_config($config);
	}

	function getError() {
		return $this->lastException;
	}

	function get_connection() {
		if ($this->con instanceof PDO) return $this->con;

		try {
			$this->con = $this->connect();
			return $this->con;
		} catch (PDOException $e) {
			$this->lastException = $e;
			$this->error = $e->getCode();
			throw $e;
		}

	}

	private function connect() {
		$config = $this->config;

		$dbDsn = "mysql:host=".$config['host'].";dbname=".$config['schema'];
		$con = new PDO( $dbDsn, $config['user'], $config['pass'] );
		$con->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		return $con;
	}

}
