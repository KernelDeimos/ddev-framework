<?php

namespace DDev\RC\Framework;

// External
use \Aura\Router\RouterContainer;
use \Zend\Diactoros\ServerRequestFactory;

/**
 * An implementation of Aura.Router
 */
class Router
{
	/**
	 * Constructs the router with optional parameter overrides.
	 *
	 * @param  iParams  Defines override parameters
	 */
	function __construct($iParams=array())
	{
		$defaults = array(
			'controller_namespace' => 'Controllers\\',
			'base_path'            => ''
		);

		$params = $iParams + $defaults;
		$this->params = $params;

		$this->instantiate_router();		
	}

	/**
	 * Runs an implementation of Aura.Router
	 */
	function run()
	{
		// Generate request object using zend-diactoros
		$request = ServerRequestFactory::fromGlobals(
			$_SERVER,
			$_GET,
			$_POST,
			$_COOKIE,
			$_FILES
		);
		
		// Process request with Aura.Router to get route
		$matcher = $this->router->getMatcher();
		$route = $matcher->match($request);

		// Determine controller class from route
		$class = null;
		{
			// Handle case if route not valid
			if ( ! $route ) {
				// Obtain closest route with failure reason
				$failedRoute = $matcher->getFailedRoute();

				// Determine error controller to load
				$handlers = null;
				switch($failedRoute->failedRule)
				{
					case 'Aura\Router\Rule\Allows':
						$handlers = array('Error405', 'ErrorX');
						break;
					case 'Aura\Router\Rule\Accepts':
						$handlers = array('Error406', 'ErrorX');
						break;
					default:
						$handlers = array('Error404', 'ErrorX');
				}

				$class = $this->get_controller_class($handlers);
			}

			// Handle case if route successful
			else {
				$class = $this->get_controller_class($route->handler);

				// Handle case if controller missing
				if ($class === false) {
					$class = $this->get_controller_class(
						array('Error404', 'ErrorX')
					);
				}
			}
		}

		// Handle case if error controller is missing
		if ($class === false) {
			print("Route failed. Additionally, no error handler was present.");
			return;
		}

		// Instantiate and run controller
		$controller = new $class($this);
		$response = $controller->main($request, $route);
	}

	function get_map()
	{
		$map = $this->router->getMap();
		return $map;
	}

	/**
	 * Instantiates Aura.Router's RouterContainer object according to
	 * instance parameters.
	 */
	private function instantiate_router()
	{
		// Instantiate router container
		$routerContainer = new RouterContainer(
			$this->params['base_path']
		);

		// Define default index route
		$map = $routerContainer->getMap();
		$map->get('index.read', '/', 'IndexController');

		// Add router container to instance
		$this->router = $routerContainer;
	}

	/**
	 * Checks if a controller of the specified name exists within the
	 * namespace for controllers as defined by the instance parameter
	 * 'controller_namespace'.
	 * <p>
	 * If a controller with a matching name exists, the full class
	 * name (i.e. including namespace) as returned. Otherwise, false
	 * is returned.
	 * <p>
	 * If an array of names is passed, the function is called
	 * recursively for each name until a controller class if found. If
	 * all calls return false, false is returned.
	 *
	 * @param  name Controller name or array of names
	 * @return Full class name of controller if found, otherwise false
	 */
	private function get_controller_class($name)
	{
		// If array, proccess each name until one exists
		if (is_array($name)) {
			// Process each name
			foreach ($name as $n)
			{
				$class = $this->get_controller_class($n);
				if ($class !== false) {
					return $class;
				}
			}
			return false;
		}

		// Generate full class name
		$ns = $this->params['controller_namespace'];
		$class = $ns.$name;

		// Check if class exists
		if (class_exists($class) === false)
			return false;

		return $class;
	}

}
