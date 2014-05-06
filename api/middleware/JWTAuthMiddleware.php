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
        $auth = $this->app->request->headers->get('Authorization');

        if (in_array($path, $this->special_paths)) {
            if ($auth != null) {
                // not allowed
                $this->app->response()->status(405);
            } else {
                $this->next->call();
            }
        } else if ($auth == null) {
            // not authenticated
            $this->app->response()->status(401);
        } else {
            try {
                $payload = JWT::decode($auth, 'secret');
                // check aud
                if (!$payload['aud'] || $payload['aud'] != 'papermill') {
                    $this->app->response()->status(401);
                }
                $this->next->call();
            } catch (UnexpectedValueException $ex) {
                $this->app->response()->status(401);
            } catch (DomainException $ex) {
                $this->app->response()->status(401);
            }
        }
    }
}
