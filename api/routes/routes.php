<?php

ORM::configure('sqlite:db/papermill.db');

require_once 'models.php';
require_once 'users.php';
require_once 'papers.php';

$app->get('/', function() {
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
               type INTEGER,
               url VARCHAR(256));');
               
    $db->exec('CREATE TABLE IF NOT EXISTS tag (
               id INTEGER PRIMARY KEY,
               name VARCHAR(256));');
               
    $db->exec('CREATE TABLE IF NOT EXISTS paper_tag (
              paper_id integer,
              tag_id integer);');

    $db->exec('CREATE TABLE IF NOT EXISTS paper_author (
              paper_id integer,
              author_id integer);');
});

?>
