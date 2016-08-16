<?php

namespace DDev\Core;

class Database
{
	function __construct($pdo)
	{
		$this->pdo = $pdo;
	}
}
