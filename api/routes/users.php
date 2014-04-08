<?php

$app->group('/user', function () use ($app) {

    /*
     * get all users
     */
    $app->get('', function () use ($app) {
        $app->response()->header("Content-Type", "application/json");
        $users = Model::factory('User')
            ->find_many();

        echo json_encode($users);
    });

    /*
     * get user by id
     */
    $app->get('/:id', function ($id) use ($app) {
        $user = Model::factory('User')->find_one($id);
        if ($user != null) {
            echo json_encode($user->as_array());
        } else {
            $app->response->setStatus(404);
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

            $user = Model::factory('User')->create();
            try {
                $user->deserialize($arr);
                $user->save();
                echo $user->serialize();
            } catch (ModelException $e)  {
                $app->response->setStatus(400);
                echo '{"error":"'.$e->getMessage().'"}';
            }
        }
    });

    $app->put('/:id', function ($id) use ($app) {
        $user = Model::factory('User')->find_one($id);
        $user->save();
        $arr = $user->as_array();
        echo json_encode($arr);
    });

    $app->delete('/:id', function ($id) use ($app) {
        $user = Model::factory('User')->find_one($id);
        if ($user != null) {
            $user->delete();
            $app->response->setStatus(204);
        } else {
            $app->response->setStatus(404);
        }
    });
});

?>
