DDev Framework
==============

A microframework and tools for developing PHP applications.

Installation
------------

### Typical Install

For a typical installation, create a composer.json in a new project
folder that looks like the following.

```json
{
	"autoload": {
		"psr-4": {
			"":"src/"
		}
	},
	"repositories": [
		{
			"type": "vcs",
			"url": "https://github.com/KernelDeimos/ddev-framework"
		}
	],
	"require": {
		"dubedev/ddev-framework": "*"
	}
}

```

Proceed by running `composer update`.
Tools will now be available under the DDev namespace.

The “autoload” key is provided as an example. It's assumed present by the documentation.

Example Use of Framework
------------------------

### Creating index.php

```php
// Require composer autoloader
require('vendor/autoload.php');

// Use router and debug messages
use \DDev\Dev\DebuggingErrorHandler;
use \DDev\RC\Framework\Router;

// Error handler (for development only)
define('DEV_MODE',true);
$errors = new DebuggingErrorHandler();

// Instantiate and run the router
$app = new Router(array(
	'base_path' => '/some/sub/folder'
));
$app->run();
```

The 'base_path' parameter is optional. Use this parameter if
the project is located in a subfolder of your webserver.

### Creating a page controller

By default, controllers are expected to be under the `\Controllers` namespace. Create a file called IndexController.php written as such:
```php
<?php
namespace Controllers;

class IndexController extends \DDev\Core\Controller {
	function main($request) {
		echo "Hello, world!";
	}
}
```

The IndexController controller is already mapped to the route '/' by default. To create new controllers, you will need to add routes.

### Adding a route

**This is a quick example. This will be refined later.**

The Aura.Router map can be accessed from the `\DDev\RC\Framework\Router` class.
```php
$app = new Router(/* ... */);
// ...
$map = $app->get_map();
$map->get('blog.read', '/', 'BlogController');
// ...
$app->run();
```

More detailed documentation for the map object is available in the documentation for Aura.Router.

### A Note About Namespaces

DDev Framework is organized into 4 main namespaces.
- `\DDev\Core` contains the fundamental base classes.
- `\DDev\Dev` contains development tools, such as debuggers.
- `\DDev\RC` contains release candidate classes. These may change drastically at any time.
- `\DDev\Framework` will contain complete implementations of framework components. Since this is currently in early development, these classes will be found under `\DDev\RC\Framework`.

Other namespaces, such as `\DDev\HTML`, contain convenient utilities. These may later be separated from this package if found necessary.
