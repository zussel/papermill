<?php

$app->group('/paper', function () use ($app) {

    /*
     * get all papers
     */
    $app->get('', function () use ($app) {
        $app->response()->header("Content-Type", "application/json");
        $papers = Model::factory('Paper')
            ->order_by_asc("title")
            ->find_many();

        $papers = array_map(function($a) {
                $arr = $a->as_array();
                $arr["year"] = intval($arr["year"]);
                return $arr; },
            $papers);
        echo json_encode($papers);
    });

    /*
     * get paper by id
     */
    $app->get('/:id', function ($id) use ($app) {
        $app->response()->header("Content-Type", "application/json");
        $paper = Model::factory('Paper')->find_one($id);
        if ($paper != null) {
            $arr = $paper->as_array();
            $arr["year"] = intval($arr["year"]);
            echo json_encode($arr);
        } else {
            $app->response->setStatus(404);
        }
    });

    /*
     * update a paper
     */
    $app->put('/:id', function ($id) use ($app) {
        $paper = Model::factory('Paper')->find_one($id);
        $data = $app->request()->getBody();
        if ($paper == null) {
            $app->response->setStatus(404);
        } else if ($data == null) {
            $app->response->setStatus(404);
        } else {
            $paper->title = $data['title'];
            $paper->year = $data['year'];
            $paper->author = $data['author'];
            $paper->url = $data['url'];
            $paper->save();
            $arr = $paper->as_array();
            $arr["year"] = intval($arr["year"]);
            echo json_encode($arr);
        }
    });

    /*
     * add a new paper
     */
    $app->post('', function () use ($app) {
        $app->response()->header("Content-Type", "application/json");
        $data = $app->request()->getBody();

        if ($data == null) {
            $app->response->setStatus(404);
        } else {
            /*
             * parse json data
             */
            $paper = Model::factory('Paper')->create();
            $paper->title = $data['title'];
            $paper->year = $data['year'];
            $paper->author = $data['author'];
            $paper->url = $data['url'];
            $paper->save();
            $arr = $paper->as_array();
            $arr["year"] = intval($arr["year"]);
            echo json_encode($arr);
        }
    });

    /*
     * delete a paper
     */
    $app->delete('/:id', function ($id) use ($app) {
        $paper = Model::factory('Paper')->find_one($id);
        if ($paper != null) {
            $paper->delete();
            $app->response->setStatus(204);
        } else {
            $app->response->setStatus(404);
        }
    });
});

?>
