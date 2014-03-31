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
            echo $paper->serialize();
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
            $paper->deserialze($data);
            $paper->save();
            echo $paper->serialize();
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
            $paper = Model::factory('Paper')->create();
            try {
                $paper->deserialize($arr);
                $paper->save();
                echo $paper->serialize();
            } catch (ModelException $e) {
                $app->response->setStatus(400);
                echo '{"error":"'.$e->getMessage().'"}';
            }
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
