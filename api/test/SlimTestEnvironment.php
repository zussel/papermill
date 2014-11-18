<?php

class SlimTestEnvironment
{
    // We support these methods for testing. These are available via
    // `this->get()` and `$this->post()`. This is accomplished with the
    // `__call()` magic method below.
    private $testingMethods = array('get', 'post', 'patch', 'put', 'delete', 'head');

    private $optionalHeader = array();

    public function __construct() {
        $this->init();
    }

    protected function init() {
        // Initialize our own copy of the slim application
        $app = new \Slim\Slim(array(
            'version'        => '0.1.0',
            'debug'          => false,
            'mode'           => 'testing',
            'log.enabled'    => true
        ));

        $GLOBALS['config'] = include __DIR__ . '/../config/config.php';

        // Include our core application file
        require_once __DIR__ . '/../config/database.php';
        require_once __DIR__ . '/../models/exceptions.php';
        require_once __DIR__ . '/../models/models.php';

        /*
        require __DIR__ . '/../utils/Upload.php';

        $app->uploader = function($c) use ($app) {
            return new Upload();
        };
        */

        require_once __DIR__ . '/../middleware/JWTAuthMiddleware.php';

        require __DIR__ . '/../routes/setup.php';
        require __DIR__ . '/../routes/users.php';
        require __DIR__ . '/../routes/profiles.php';
        require __DIR__ . '/../routes/auth.php';
        require __DIR__ . '/../routes/papers.php';

        $app->add(new \Slim\Middleware\ContentTypes());
        $app->add(new \JWTAuthMiddleware());

        // Establish a local reference to the Slim app object
        $this->app = $app;

        $this->optionalHeader = $this->prepare_header($this->optionalHeader);
        $this->configure_database();
    }

    // Abstract way to make a request to SlimPHP, this allows us to mock the
    // slim environment
    public function request($method, $path, $body = '', $query = '', $optionalHeaders = array())
    {
        // Prepare a mock environment
        \Slim\Environment::mock(array_merge(array(
            'REQUEST_METHOD' => strtoupper($method),
            'PATH_INFO'      => $path,
            'SERVER_NAME'    => 'localhost',
            'QUERY_STRING'   => (isset($query) ? $query : ''),
            'slim.input'     => (isset($body) ? $body: '')
        ), array_merge($this->optionalHeader, $optionalHeaders)));

        // Establish some useful references to the slim app properties
        $this->request  = $this->app->request();
        $this->response = $this->app->response();

        // Execute our app
        $this->app->run();

        return $this->app;
    }

    // Implement our `get`, `post`, and other http operations
    public function __call($method, $arguments) {
        if (in_array($method, $this->testingMethods)) {
            list($path, $formVars, $query, $headers) = array_pad($arguments, 4, array());
            return $this->request($method, $path, $formVars, $query, $headers);
        }
        throw new \BadMethodCallException(strtoupper($method) . ' is not supported');
    }

}