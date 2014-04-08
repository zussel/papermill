<?php
/**
 * Created by PhpStorm.
 * User: sascha
 * Date: 4/8/14
 * Time: 3:17 PM
 */

$app->group('/author', function () use ($app) {

    /*
     * get all authors
     */
    $app->get('', function () use ($app) {
        $app->response()->header("Content-Type", "application/json");
        $authors = Model::factory('Author')
            ->order_by_asc("last_name")
            ->find_many();

        $authors = array_map(function($a) {
                $arr = $a->as_array();
                $arr["first_name"] = utf8_encode($arr["first_name"]);
                $arr["last_name"] = utf8_encode($arr["last_name"]);
                return $arr; },
            $authors);
        echo json_encode($authors);
    });

    /*
     * get author by id
     */
    $app->get('/:id', function ($id) use ($app) {
        $author = Model::factory('Author')->find_one($id);
        if ($author != null) {
            $arr = $author->as_array();
            $arr["first_name"] = utf8_encode($arr["first_name"]);
            $arr["last_name"] = utf8_encode($arr["last_name"]);
            echo json_encode($arr);
        } else {
            $app->response->setStatus(404);
        }
    });

    $app->post('', function () use ($app) {
        $author = Model::factory('Author')->create();
        $author->save();
        $arr = $author->as_array();
        $arr["first_name"] = utf8_encode($arr["first_name"]);
        $arr["last_name"] = utf8_encode($arr["last_name"]);
        echo json_encode($arr);
    });

    $app->put('/:id', function ($id) use ($app) {
        $author = Model::factory('Author')->find_one($id);
        $author->save();
        $arr = $author->as_array();
        $arr["first_name"] = utf8_encode($arr["first_name"]);
        $arr["last_name"] = utf8_encode($arr["last_name"]);
        echo json_encode($arr);
    });

    $app->delete('/:id', function ($id) use ($app) {
        $author = Model::factory('Author')->find_one($id);
        if ($author != null) {
            $author->delete();
            $app->response->setStatus(204);
        } else {
            $app->response->setStatus(404);
        }
    });
});
