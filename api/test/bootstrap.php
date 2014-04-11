<?php
/**
 * Created by IntelliJ IDEA.
 * User: sascha
 * Date: 3/28/14
 * Time: 4:41 PM
 */

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('UTC');

require_once __DIR__ . '/../vendor/autoload.php';

class Slim_Framework_TestCase extends PHPUnit_Framework_TestCase
{
    // We support these methods for testing. These are available via
    // `this->get()` and `$this->post()`. This is accomplished with the
    // `__call()` magic method below.
    private $testingMethods = array('get', 'post', 'patch', 'put', 'delete', 'head');

    // Run for each unit test to setup our slim app environment
    public function setup()
    {
        // Initialize our own copy of the slim application
        $app = new \Slim\Slim(array(
            'version'        => '0.1.0',
            'debug'          => false,
            'mode'           => 'testing',
            'log.enabled'    => true
        ));
        // Include our core application file
        require_once __DIR__ . '/../config/database.php';
        require_once __DIR__ . '/../models/exceptions.php';
        require_once __DIR__ . '/../models/models.php';

        require_once __DIR__ . '/../middleware/JWTAuthMiddleware.php';

        require __DIR__ . '/../routes/setup.php';
        require __DIR__ . '/../routes/users.php';
        require __DIR__ . '/../routes/auth.php';
        require __DIR__ . '/../routes/papers.php';

        $app->add(new \Slim\Middleware\ContentTypes());
        //$app->add(new \JWTAuthMiddleware());

        // Establish a local reference to the Slim app object
        $this->app = $app;
    }

    // Abstract way to make a request to SlimPHP, this allows us to mock the
    // slim environment
    private function request($method, $path, $body = '', $optionalHeaders = array())
    {
        // Capture STDOUT
        ob_start();

        // Prepare a mock environment
        \Slim\Environment::mock(array_merge(array(
            'REQUEST_METHOD' => strtoupper($method),
            'PATH_INFO'      => $path,
            'SERVER_NAME'    => 'localhost',
//            'SERVER_NAME'    => 'papermill.local',
            'slim.input'     => $body
        ), $optionalHeaders));

        // Establish some useful references to the slim app properties
        $this->request  = $this->app->request();
        $this->response = $this->app->response();

        // Execute our app
        $this->app->run();

        // Return the application output. Also available in `response->body()`
        return ob_get_clean();
    }

    // Implement our `get`, `post`, and other http operations
    public function __call($method, $arguments) {
        if (in_array($method, $this->testingMethods)) {
            list($path, $formVars, $headers) = array_pad($arguments, 3, array());
            return $this->request($method, $path, $formVars, $headers);
        }
        throw new \BadMethodCallException(strtoupper($method) . ' is not supported');
    }
}

/* End of file bootstrap.php */
