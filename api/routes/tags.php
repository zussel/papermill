<?php

$app->group('/tag', function () use ($app) {

    $app->get('', function () use ($app) {
        $app->response()->header("Content-Type", "application/json");
        $tags = Model::factory('Tag')
            ->find_many();

        echo json_encode($tags->as_array());
    });

    $app->get('/:id', function ($id) use ($app) {
        $tag = Model::factory('Tag')
            ->find_one($id);

        echo json_encode($tag->as_array());
    })->conditions(array('id' => '[1-9][0-9]*'));

    $app->post('', function () use ($app) {
    });
});