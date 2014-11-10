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

        if (!isset($_FILES['file'])) {
            $app->response->setStatus(500);
            echo '{"error":"missing paper file"}';
            return;
        };

        $file = $_FILES['file'];
        $count = count($file['name']);

        if ($count === 0) {
            echo '{"error":"missing paper file"}';
            return;
        }

        if ($file['error'] === 0) {
            $name = uniqid();
            if (move_uploaded_file($file['tmp_name'], 'uploads/papers/' . $name) === true) {
                $papers[] = array('url' => '/uploads/papers/' . $name, 'name' => $file['name']);
            }
        }

        $json = $app->request->post('paper');

        if ($json == null) {
            $app->response->setStatus(404);
            echo 'data null';
        } else {
            if (is_string($json)) {
              $arr = json_decode($json, true);
            } else {
              $arr = $json;
            }

            $arr['url'] = $papers[0]['url'];
//            $arr['name'] = $papers[0]['name'];

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

