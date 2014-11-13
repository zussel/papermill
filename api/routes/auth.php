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
                 * create user and author
                 * return success and user
                 */
                $user = Model::factory('User')->create();
                try {
                    $user->deserialize($arr);
                    /*
                     * handle password separately
                     */
                    $user->passwd_salt = openssl_random_pseudo_bytes(16);
                    $user->passwd = sha1($arr['passwd'] . $user->passwd_salt);
                    $user->save();

                    $arr['user_id'] = $user->id;

                    $author = Model::factory('Profile')->create();
                    $author->deserialize($arr);
                    $author->save();

                    echo $user->serialize();
                } catch (ModelException $e) {
                    $app->response->setStatus(400);
                    echo '{"error":"'.$e->getMessage().'"}';
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
