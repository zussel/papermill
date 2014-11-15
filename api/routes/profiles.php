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
                return $arr; },
            $profiles);
        echo json_encode($profiles);
    });

    /*
     * find profiles by name
     * i.e.: find?query=name==Hans,first_name==Hans,last_name==Andersen
     *
     * results in
     *
     * select * from profile where name like %Hans% or first_name like %Hans% or last_name like %Andersen%
     * $tokens =  preg_match_all('/(\w+)(==|!=|=gt=|=ge=|=le=|=lt=)([\w\.]+)([,;])?/', $query, $matches, PREG_SET_ORDER);
     */
    /*
     * get profile by id
     */
    $app->get('/:id', function ($id) use ($app) {
        $profile = Model::factory('Profile')->find_one($id);
        if ($profile != null) {
            echo json_encode($profile->as_array());
        } else {
            $app->response->setStatus(404);
            echo '{"error": "unknown profile with id '.$id.'"}';
        }
    })->conditions(array('id' => '[1-9][0-9]*'));

    $app->get('/find', function () use ($app) {
        // get query
        $term = $app->request()->get('term');
        // parse query
        if (isset($term)) {
            $profiles = Model::factory('Profile')
                ->where_raw('(first_name like ? OR last_name like ?)',
                    array('%'.$term.'%', '%'.$term.'%'))
                ->find_array();
            for ($i = 0; $i < count($profiles); ++$i) {
                $profiles[$i]['full_name'] = $profiles[$i]['first_name'].' '.$profiles[$i]['last_name'];
            }
            echo json_encode($profiles);
        } else {
            $profiles = Model::factory('Profile')
                ->order_by_asc("last_name")
                ->find_array();
            for ($i = 0; $i < count($profiles); ++$i) {
                $profiles[$i]['full_name'] = $profiles[$i]['first_name'].' '.$profiles[$i]['last_name'];
            }
            echo json_encode($profiles);
        }
    });

    $app->post('', function () use ($app) {
        $json = $app->request()->getBody();

        if ($json == null) {
            $app->response->setStatus(400);
            echo '{"error": "no profile data"}';
        } else if (is_string($json)) {
            $json = json_decode($json, true);
        } else {
            /*
             * parse json data
             */
            $profile = Model::factory('Profile')->create($json);
            if (!$profile->save()) {
                $app->response->setStatus(400);
                echo '{"error":"couldn\'t create profile"}';
            } else {
                echo json_encode($profile->as_array());
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
