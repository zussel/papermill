<?php
/**
 * Created by PhpStorm.
 * User: sascha
 * Date: 4/8/14
 * Time: 3:17 PM
 */

$app->group('/profile', function () use ($app) {

    /*
     * get all profiles
     */
    $app->get('', function () use ($app) {
        $app->response()->header("Content-Type", "application/json");
        $profiles = Model::factory('Profile')
            ->order_by_asc("last_name")
            ->find_many();

        $profiles = array_map(function($a) {
                $arr = $a->serialize();
                /*
                $arr = $a->as_array();
                $arr["name"] = utf8_encode($arr["name"]);
                $arr["first_name"] = utf8_encode($arr["first_name"]);
                $arr["last_name"] = utf8_encode($arr["last_name"]);
                */
                return $arr; },
            $profiles);
        echo json_encode($profiles);
    });

    /*
     * get profile by id
     */
    $app->get('/:id', function ($id) use ($app) {
        $profile = Model::factory('Profile')->find_one($id);
        if ($profile != null) {
            echo $profile->serialize();
            /*
            $arr = $profile->as_array();
            $arr["first_name"] = utf8_encode($arr["first_name"]);
            $arr["last_name"] = utf8_encode($arr["last_name"]);
            echo json_encode($arr);
            */
        } else {
            $app->response->setStatus(404);
        }
    });

    /*
     * find profiles by name
     * i.e.: find?query=name==Hans,first_name==Hans,last_name==Andersen
     *
     * results in
     *
     * select * from profile where name like %Hans% or first_name like %Hans% or last_name like %Andersen%
     */
    $app->get('find', function () use ($app) {
        // get query
        $query = $app->request()->get('query');
        // parse query
        if (isset($query)) {
            $tokens =  preg_match_all('/(\w+)(==|!=|=gt=|=ge=|=le=|=lt=)([\w\.]+)([,;])?/', $query, $matches, PREG_SET_ORDER);

        } else {
            $profiles = Model::factory('Profile')
                ->order_by_asc("last_name")
                ->find_many();
            $profiles = array_map(function($a) {
                    $arr = $a->serialize();
                    /*
                    $arr = $a->as_array();
                    $arr["name"] = utf8_encode($arr["name"]);
                    $arr["first_name"] = utf8_encode($arr["first_name"]);
                    $arr["last_name"] = utf8_encode($arr["last_name"]);
                    */
                    return $arr; },
                $profiles);
            echo json_encode($profiles);
        }
    });

    $app->post('', function () use ($app) {
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
             * parse json data
             */
            $profile = Model::factory('Profile')->create();
            try {
                $profile->deserialize($arr);
                $profile->save();
                echo $profile->serialize();
            } catch (ModelException $e) {
                $app->response->setStatus(400);
                echo '{"error":"'.$e->getMessage().'"}';
            }
        }
    });

    $app->put('/:id', function ($id) use ($app) {
        $profile = Model::factory('Profile')->find_one($id);
        $profile->save();
        $arr = $profile->as_array();
        $arr["first_name"] = utf8_encode($arr["first_name"]);
        $arr["last_name"] = utf8_encode($arr["last_name"]);
        echo json_encode($arr);
    });

    $app->delete('/:id', function ($id) use ($app) {
        $profile = Model::factory('Profile')->find_one($id);
        if ($profile != null) {
            $profile->delete();
            $app->response->setStatus(204);
        } else {
            $app->response->setStatus(404);
        }
    });
});
