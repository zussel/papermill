<?php
/**
 * Created by PhpStorm.
 * User: sascha
 * Date: 07.04.14
 * Time: 22:02
 */

class JWTAuthMiddleware extends \Slim\Middleware
{
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

        if (($path == "/auth/login" || $path="/auth/signin") && $auth != null) {
            /*
             * TODO: handle login when already logged in
             *       or on signin page
             */
        } else if ($path != "/auth/login" && $auth == null) {
            // not authenticated: return 401
            $this->app->response()->status(401);
        } else {
            // TODO: check auth
            $this->next->call();
        }
    }
}