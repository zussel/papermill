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

    private $optionalHeader = array();

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

        $GLOBALS['config'] = include __DIR__ . '/../config/config.php';

        // Include our core application file
        require_once __DIR__ . '/../config/database.php';
        require_once __DIR__ . '/../models/exceptions.php';
        require_once __DIR__ . '/../controller/paper.php';
        require_once __DIR__ . '/../models/models.php';

        require_once __DIR__ . '/../utils/PaperUpload.php';

        $app->uploader = function($c) use ($app) {
            return new PaperUpload();
        };

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

        // setup db
        ORM::configure('sqlite::memory:');

        setup_db();

        $this->configure_database();
    }

    public function tearDown()
    {
        drop_db();
    }

    // Abstract way to make a request to SlimPHP, this allows us to mock the
    // slim environment
    private function request($method, $path, $body = '', $query = '', $optionalHeaders = array())
    {
        // Capture STDOUT
        ob_start();

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

        // Return the application output. Also available in `response->body()`
        return ob_get_clean();
    }

    // Implement our `get`, `post`, and other http operations
    public function __call($method, $arguments) {
        if (in_array($method, $this->testingMethods)) {
            list($path, $formVars, $query, $headers) = array_pad($arguments, 4, array());
            return $this->request($method, $path, $formVars, $query, $headers);
        }
        throw new \BadMethodCallException(strtoupper($method) . ' is not supported');
    }

    protected function appendHeader($optionalHeader) {
        if (!is_array($optionalHeader)) {
            return;
        }
        $this->optionalHeader = array_merge($this->optionalHeader, $optionalHeader);
    }

    protected function prepare_header($optionalHeader) {
        return $optionalHeader;
    }

    protected function configure_database() {
        // setup db
        $salt = openssl_random_pseudo_bytes(16);
        $passwd = sha1('secret' . $salt);

        /*
         * insert user
         */
        $user = Model::factory('User')->create();
        $user->email = 'a@a.de';
        $user->passwd = $passwd;
        $user->passwd_salt = $salt;
        $user->save();

        /*
        $db = ORM::get_db();

        $sql = 'INSERT INTO user (email, passwd, passwd_salt) VALUES ("a@a.de", "' + $passwd + '", "' + $salt + '")';
        $db->exec($sql);
        */
    }

    protected function createJWTToken($userid) {
        $expiry = 24 * 60 * 60;
        $key = $GLOBALS['config']['jwt-secret'];
        $token['id'] = $userid;
        $token['aud'] = 'papermill';
        $token['exp'] = time() + $expiry;

        return JWT::encode($token, $key);
    }
}

/* End of file bootstrap.php */
