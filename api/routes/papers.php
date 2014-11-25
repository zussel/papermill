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

        $uploader = $app->uploader;

        $uploaded = $uploader->upload($_FILES['file'], 'uploads/papers/');
        if (!$uploaded) {
            echo '{"error":"couldn\'t upload file"}';
            return;
        }

        $json = $uploader->paper($app->request);

        if ($json == null) {
            $app->response->setStatus(404);
            echo 'data null';
        } else if (is_string($json)) {
            $json = json_decode($json, true);
        } else if (!is_array($json)) {
            $app->response->setStatus(400);
            echo '{"error": "invalid paper data"}';
        }
        // validate data
        if (!isset($json['title'])) {
            $app->response->setStatus(400);
            echo '{"error": "missing title"}';
        } else if (!isset($json['year'])) {
            $app->response->setStatus(400);
            echo '{"error": "missing year"}';
        } else if (!isset($json['authors']) || !is_array($json['authors'])) {
            $app->response->setStatus(400);
            echo '{"error": "missing or invalid authors"}';
        } else if (!isset($json['tags']) || !is_array($json['tags'])) {
            $app->response->setStatus(400);
            echo '{"error": "missing or invalid tags"}';
        }
        /*
         * set file data
         */
        $json['url'] = $uploaded['url'];
        $json['size'] = $uploaded['size'];
        /*
         * parse json data
         */
        $paper = Model::factory('Paper')->create();

        $paper->title = $json['title'];
        $paper->year = $json['year'];
        $paper->save();

        /*
         * validate authors
         */
        foreach($json['authors'] as $author) {
            if ($author['id'] === null) {
                // new author insert on db
                $newAuthor = Model::factory('Author')->create();
            }
        }

        /*
         * validate tags
         */
        foreach($json['tags'] as $tag) {
            if ($tag['id'] === null) {
                // new tag insert on db
                $newTag = Model::factory('Tag')->create();
            }
        }

        echo json_encode($paper->as_array());
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

