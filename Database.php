<?php

namespace DDev;

class Database
{
	function __construct($pdo)
	{
		$this->pdo = $pdo;
	}
}
