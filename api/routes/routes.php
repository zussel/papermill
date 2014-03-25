<?php

ORM::configure('sqlite:db/papermill.db');

$app->get('/', function() {
});

$app->get('/paper', function () use ($app) {
  
    $papers = array(
        array(
            'title' => 'Paper 1',
            'auhor' => 'Gabi Kühl',
            'published' => array(
                'by'=>'PaperPress',
                'year'=> 2011
            ),
            'type'=> 'PDF',
            'tags'=> array(
                'Paläontolgy',
                'Fossils',
                'Kambrium'
            )
        )
    );

    $app->response()->header('Content-Type', 'application/json; charset=utf-8');

    echo json_encode($papers);
});

$app->get('/setup', function() use ($app) {
    /*
     * create user table consisting of
     * - unique id (id)
     * - username (name)
     * - first name (first_name)
     * - last name (last_name)
     * - email address (email)
     * - password (passwd)
     */
    $db = ORM::get_db();
    
    $db->exec('CREATE TABLE IF NOT EXISTS user (
               id INTEGER PRIMARY KEY,
               first_name VARCHAR(256),
               last_name VARCHAR(256),
               email VARCHAR(256),
               passwd VARCHAR(256));');

    $db->exec('CREATE TABLE IF NOT EXISTS paper (
               id INTEGER PRIMARY KEY,
               owner INTEGER,
               year INTEGER,
               title VARCHAR(256),
               author VARCHAR(256));');

});

?>
