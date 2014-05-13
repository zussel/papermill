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
                $arr = $a->as_array();
                $arr["first_name"] = utf8_encode($arr["first_name"]);
                $arr["last_name"] = utf8_encode($arr["last_name"]);
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
            $arr = $profile->as_array();
            $arr["first_name"] = utf8_encode($arr["first_name"]);
            $arr["last_name"] = utf8_encode($arr["last_name"]);
            echo json_encode($arr);
        } else {
            $app->response->setStatus(404);
        }
    });

    $app->post('', function () use ($app) {
        $profile = Model::factory('Profile')->create();
        $profile->save();
        $arr = $profile->as_array();
        $arr["first_name"] = utf8_encode($arr["first_name"]);
        $arr["last_name"] = utf8_encode($arr["last_name"]);
        echo json_encode($arr);
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
