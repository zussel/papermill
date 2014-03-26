<?php

$app->group('/user', function () use ($app) {

  /*
   * get all users
   */
  $app->get('', function () use ($app) {
    $app->response()->header("Content-Type", "application/json");
    $users = Model::factory('User')
      ->order_by_asc("last_name")
      ->find_many();
    
    $users = array_map(function($a) {
      $arr = $a->as_array();
      $arr["first_name"] = utf8_encode($arr["first_name"]);
      $arr["last_name"] = utf8_encode($arr["last_name"]);
      return $arr; },
    $users);
    echo json_encode($users);
  });

  /*
   * get user by id
   */
  $app->get('/:id', function ($id) use ($app) {
    $user = Model::factory('User')->find_one($id);
    if ($user != null) {
      $arr = $user->as_array();
      $arr["first_name"] = utf8_encode($arr["first_name"]);
      $arr["last_name"] = utf8_encode($arr["last_name"]);
      echo json_encode($arr);
    } else {
      $app->response->setStatus(404);
    }
  });

  $app->post('', function () use ($app) {
    $user = Model::factory('User')->create();
    $user->save();
    $arr = $user->as_array();
    $arr["first_name"] = utf8_encode($arr["first_name"]);
    $arr["last_name"] = utf8_encode($arr["last_name"]);
    echo json_encode($arr);
  });

  $app->put('/:id', function ($id) use ($app) {
    $user = Model::factory('User')->find_one($id);
    $user->save();
    $arr = $user->as_array();
    $arr["first_name"] = utf8_encode($arr["first_name"]);
    $arr["last_name"] = utf8_encode($arr["last_name"]);
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
