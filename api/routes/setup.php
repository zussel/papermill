<?php

$app->get('/setup', function() use ($app) {
  setup_db();

  echo "Database created";

  $roles = array('admin', 'user', 'guest');
  # find roles
  $adminrole = null;
  foreach($roles as $rolename) {
    $role = Model::factory('Role')->where_equal('name', $rolename)->find_one();
    if ($role == null) {
      $role = Model::factory('Role')->create();
      $role->name = $rolename;
      $role->save();
    }
    if ($role->name === 'admin') {
      $adminrole = $role;
    }
  }
  # find admin
  $user = Model::factory('User')->find_one(1);

  if ($user == null) {
    # create user admin
    $user = Model::factory('User')->create();
    $user->email = 'admin@admin.net';
    $user->role = $adminrole->id;
    $user->passwd_salt = openssl_random_pseudo_bytes(16);
    $user->passwd = sha1('admin' . $user->passwd_salt);
    $user->save();

    $profile = Model::factory('Profile')->create();
    $profile->user_id = $user->id;
    $profile->name = 'admin';
    $profile->save();
  }

  echo "Admin user created";
});

$app->get('/clear', function() use ($app) {
  clear_tables();
  echo "Tables cleared";
});

$app->get('/drop', function() use ($app) {
  drop_db();
  echo "Tables dropped";
});

$app->get('/create/authors', function() use ($app) {
  $db = ORM::get_db();

  $db->exec('INSERT INTO profile (name, first_name, last_name, user_id, active) VALUES ("bruce", "Bruce", "Willis", 0, 0)');
  $db->exec('INSERT INTO profile (name, first_name, last_name, user_id, active) VALUES ("arnold", "Arnold", "Schwarzenegger", 0, 0)');
  $db->exec('INSERT INTO profile (name, first_name, last_name, user_id, active) VALUES ("sly", "Sylvester", "Stalone", 0, 0)');
  $db->exec('INSERT INTO profile (name, first_name, last_name, user_id, active) VALUES ("steve", "Steve", "Carrel", 0, 0)');
  $db->exec('INSERT INTO profile (name, first_name, last_name, user_id, active) VALUES ("jim", "Jim", "Carrey", 0, 0)');
});

