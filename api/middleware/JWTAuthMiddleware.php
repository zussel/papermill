<?php
/**
 * Created by PhpStorm.
 * User: sascha
 * Date: 07.04.14
 * Time: 22:02
 */

class JWTAuthMiddleware extends \Slim\Middleware
{
    /**
     * Regular expression to extract token from the HTTP headers
     * @var string
     */
    protected $bearerTokenRegEx = '/Bearer\s(\S+)/';

    var $special_paths = array("/auth/login", "/auth/signin", "/setup", "/drop", "/clear", "/icon.ico");

    public function call()
    {
        /*
         * get resource uri
         */
        $path = $this->app->request->getResourceUri();
        /*
         * get auth header
         */
//        $request = $this->app->request;
        $headers = $this->app->request->headers();

        $headers = $this->app->environment;
        if (in_array($path, $this->special_paths)) {
            if ($this->hasAuthentication($headers)) {
                // not allowed
                $this->app->response()->status(405);
            } else {
                $this->next->call();
            }
        } else if (!$this->hasAuthentication($headers)) {
            // not authenticated
            $this->app->response()->status(401);
            $this->app->response()->body(json_encode(array(
                'error' => 'not authenticated'
            )));
        } else {
            try {
                if ($this->authenticate($headers)) {
                    $this->next->call();
                } else {
                    $this->app->response()->status(401);
                    $this->app->response()->body(json_encode(array('error' => 'invalid aud')));
                }
            } catch (UnexpectedValueException $ex) {
                $this->app->response()->status(401);
                $this->app->response()->body(json_encode(array('error' => 'unexpected value: '.$ex->getMessage())));

            } catch (DomainException $ex) {
                $this->app->response()->status(401);
                $this->app->response()->body(json_encode(array('error' => 'domain exception: '.$ex->getMessage())));
            }
        }
    }
    private function hasAuthentication($headers) {
        return isset($headers['HTTP_AUTHORIZATION']) && !empty($headers['HTTP_AUTHORIZATION']);
//        return isset($headers['Authorization']) && !empty($headers['Authorization']);
    }
    private function authenticate($headers) {
//        preg_match($this->bearerTokenRegEx, $headers['Authorization'], $hits);
        preg_match($this->bearerTokenRegEx, $headers['HTTP_AUTHORIZATION'], $hits);
        $auth = $hits[1];
        $payload = JWT::decode($auth, $GLOBALS['config']['jwt-secret']);
        // check aud
        if (empty($payload->aud) || $payload->aud != 'papermill') {
            return false;
        } else {
            return true;
        }
    }
}
