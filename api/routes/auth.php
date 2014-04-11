<?php
/**
 * Created by PhpStorm.
 * User: sascha
 * Date: 07.04.14
 * Time: 21:31
 */

$app->group('/auth', function () use ($app) {

    /*
     * login a user
     */
    $app->post('/login', function () use ($app) {
        /*
         *
         */
        $data = $app->request()->getBody();

        if ($data == null) {
            $app->response->setStatus(404);
            echo '{"error": "no credentials"}';
        } else {
            if (is_string($data)) {
                $arr = json_decode($data, true);
            } else {
                $arr = $data;
            }
            /*
             * get user
             */
            $user = User::where('email', $arr['email'])->find_one();

            if ($user) {
                /*
                 * check password
                 */
                if ($user->passwd != $arr['passwd']) {
                    $app->response->setStatus(400);
                    echo '{"error":"invalid password"}';
                } else {
                    /*
                     * valid login information
                     * authentication expires after
                     * 24 hours (in seconds)
                     */
                    $expiry = 24 * 60 * 60;
                    $key = 'secret';
                    $token = $user->as_array();
                    $token['iss'] = 'papermill';
                    $token['exp'] = time() + $expiry;

                    $jwt = JWT::encode($token, $key);

                    echo '{ "token": "'.$jwt.'"}';
                }

            } else {
                $app->response->setStatus(400);
                echo '{"error":"unknown user"}';
            }
        }
    });

    $app->post('/signin', function () use ($app) {
        /*
         * register a new user / author
         * we need:
         * - email (unique)
         * - password
         * - first name
         * - last name
         */
        $data = $app->request()->getBody();

        if ($data == null) {
            $app->response->setStatus(404);
            echo '{"error": "no credentials"}';
        } else {
            if (is_string($data)) {
                $arr = json_decode($data, true);
            } else {
                $arr = $data;
            }
            /*
             * check if user exists
             */
            $user = User::where('email', $arr['email'])->find_one();

            if ($user) {
                /*
                 * user exists response error
                 */
                $app->response->setStatus(404);
                echo '{"error":"user already exists"}';
            } else {

            }
        }
    });

    $app->put('/logout', function () use ($app) {

    });
});
