<?php

namespace DDev\Dev;

class DebuggingErrorHandler
{
	function __construct() {
		set_error_handler(function($errno, $errstr, $errfile, $errline) {
			if (DEV_MODE) echo "de errno:".$errno.";errstr:".$errstr.";errfile:".$errfile.";errline:".$errline;
			/*if (DEV_MODE && $errno==8) {
				var_dump(debug_backtrace());
			}*/
			switch ($errno) {
				case E_RECOVERABLE_ERROR:
				case E_USER_ERROR:
					throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
					break;
				case E_WARNING:
				case E_USER_WARNING:
					break;
				case E_NOTICE:
				case E_USER_NOTICE:
					break;
				default:
					
					break;
			}
			$notice = array();
			$notice['errno'] = $errno;
			$notice['errstr'] = $errstr;
			$notice['errfile'] = $errfile;
			$notice['errline'] = $errline;
		});
		register_shutdown_function(function() {
			$var = error_get_last();
			if ($var) {
				echo "<h1>Terminating Error</h1>";
				if (DEV_MODE) {
					echo "<br /><br />DEV MODE IS ON:<br /><pre>";
					print_r($var);
				}
				echo "</pre>";
			}
		});
	}
}
