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
        $json = $app->request()->getBody();

        if ($json == null) {
            $app->response->setStatus(400);
            echo '{"error": "no credentials"}';
        } else if (is_string($json)) {
            $json = json_decode($json, true);
        } else if (!is_array($json)) {
            // data is invalid
            $app->response->setStatus(400);
            echo '{"error": "invalid profile data"}';
        }

        if (!isset($json['email'])) {
            $app->response->setStatus(400);
            echo '{"error": "missing email"}';
        } else if (!isset($json['passwd'])) {
            $app->response->setStatus(400);
            echo '{"error": "missing password"}';
        } else {
            /*
             * get user
             */
            $user = User::where('email', $json['email'])->find_one();

            if ($user) {
                /*
                 * check password
                 */
                $hashed = sha1($json['passwd'] . $user->passwd_salt);
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
        $json = $app->request->getBody();
        $app->response->header("Content-Type", "application/json");

        if ($json == null) {
            $app->response->setStatus(400);
            echo '{"error": "no credentials"}';
        } else if (is_string($json)) {
            $json = json_decode($json, true);
        } else if (!is_array($json)) {
            // data is invalid
            $app->response->setStatus(400);
            echo '{"error": "invalid profile data"}';
        }

        /*
         * check if user exists
         */
        if (!isset($json['user'])) {
            $app->response->setStatus(400);
            echo '{"error": "missing user data"}';
        } else if (!isset($json['profile'])) {
            $app->response->setStatus(400);
            echo '{"error": "missing profile data"}';
        } else if (!isset($json['user']['email'])) {
            $app->response->setStatus(400);
            echo '{"error": "missing user email"}';
        } else {
            $user = User::where('email', $json['user']['email'])->find_one();

            if ($user) {
                /*
                 * user exists response error
                 */
                $app->response->setStatus(400);
                echo '{"error":"user already exists"}';
            } else if (!isset($json['user']['passwd'])) {
                $app->response->setStatus(400);
                echo '{"error": "missing user passwd"}';
            } else {
                /*
                 * create user and profile
                 * return success and user and profile
                 */
                $user = Model::factory('User')->create();
                $user->email = $json['user']['email'];
                // set password
                $user->passwd_salt = openssl_random_pseudo_bytes(16);
                $user->passwd = sha1($json['user']['passwd'] . $user->passwd_salt);

                if (!$user->save()) {
                    $app->response->setStatus(400);
                    echo '{"error":"couldn\' create user"}';
                } else {

                    $profile = Model::factory('Profile')->create($json['profile']);
                    $profile->active = true;
                    $profile->user_id = $user->id;

                    if (!$profile->save()) {
                        $user->delete();
                        $app->response->setStatus(400);
                        echo '{"error":"couldn\' create profile"}';
                    }
                    echo json_encode(array(
                        'user' => array(
                            'email' => $user->email,
                            'id' => $user->id,
                        ),
                        'profile' => $profile->as_array()
                    ));
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
        // unset user in app
        echo "logged out";
    });
});
