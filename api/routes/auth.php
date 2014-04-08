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
            echo 'data null';
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
                     */

                    $key = 'secret';
                    $token = $user->as_array();

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

    });

    $app->put('/logout', function () use ($app) {

    });
});
