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
                $hashed = sha1($arr['passwd'] . $user->passwd_salt);
                if ($user->passwd != $hashed) {
                    $app->response->setStatus(400);
                    echo '{"error":"invalid password"}';
                } else {
                    /*
                     * valid login information
                     * authentication expires after
                     * 24 hours (in seconds)
                     */
                    $expiry = 24 * 60 * 60;
                    $key = $GLOBALS['config']['jwt-secret'];
                    $token['id'] = $user->id;
                    $token['aud'] = 'papermill';
                    $token['exp'] = time() + $expiry;

                    $jwt = JWT::encode($token, $key);

                    echo '{ "token": "'.$jwt.'" }';
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
        $data = $app->request->getBody();
        $app->response->header("Content-Type", "application/json");

        if ($data == null) {
            $app->response->setStatus(400);
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
            if (!$arr['user'] || !$arr['user']['email']) {
                echo '{"error": "missing email"}';
                return;
            }
            $user = User::where('email', $arr['user']['email'])->find_one();

            if ($user) {
                /*
                 * user exists response error
                 */
                $app->response->setStatus(400);
                echo '{"error":"user already exists"}';
            } else {
                /*
                 * create user and profile
                 * return success and user and profile
                 */
                $user = Model::factory('User')->create();
                $user->email = $arr['user']['email'];
                // set password
                $user->passwd_salt = openssl_random_pseudo_bytes(16);
                $user->passwd = sha1($arr['user']['passwd'] . $user->passwd_salt);

                if (!$user->save()) {
                    $app->response->setStatus(400);
                    echo '{"error":"couldn\' create user"}';
                } else {

                    $profile = Model::factory('Profile')->create($arr['profile']);
                    $profile->active = true;
                    $profile->user_id = $user->id;

                    if (!$profile->save()) {
                        $user->delete();
                        $app->response->setStatus(400);
                        echo '{"error":"couldn\' create profile"}';
                    }
                    $result = json_encode(array(
                        'user' => array(
                            'email' => $user->email,
                            'id' => $user->id,
                        ),
                        'profile' => $profile->as_array()
                    ));
                    echo $result;
                }
            }
        }
    });

    /**
     * Confirm the new user
     */
    $app->get('/confirm', function() use ($app) {

    });

    /**
     * Logout the given user
     */
    $app->get('/logout/:id', function ($id) use ($app) {

    });
});
