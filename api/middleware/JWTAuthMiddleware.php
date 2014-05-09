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
    protected $bearerTokenRegEx = '#Bearer\s(\S+)#';

    var $special_paths = array("/auth/login", "/auth/signin", "/setup");

    public function call()
    {
        /*
         * get resource uri
         */
        $path = $this->app->request->getResourceUri();
        /*
         * get auth header
         */
        $headers = apache_request_headers();

        if (in_array($path, $this->special_paths)) {
            if (isset($headers['Authorization'])) {
                // not allowed
                $this->app->response()->status(405);
            } else {
                $this->next->call();
            }
        } else if (!isset($headers['Authorization'])) {
            // not authenticated
            $this->app->response()->status(401);
            $this->app->response()->body(json_encode(array(
                'error' => 'not authenticated'
            )));
        } else {
            try {
                $auth = $headers['Authorization'];
                $payload = JWT::decode($auth, $GLOBALS['config']['jwt-secret']);
                // check aud
                if (empty($payload->aud) || $payload->aud != 'papermill') {
                    $this->app->response()->status(401);
                    $this->app->response()->body(json_encode(array('error' => 'invalid aud')));
                } else {
                    $this->next->call();
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
}
