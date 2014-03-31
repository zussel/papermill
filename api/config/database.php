<?php
/**
 * Created by PhpStorm.
 * User: sascha
 * Date: 31.03.14
 * Time: 21:46
 */
function setup_db() {
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
               email VARCHAR(256),
               passwd VARCHAR(256));');

    $db->exec('CREATE TABLE IF NOT EXISTS author (
               id INTEGER PRIMARY KEY,
               user_id INTEGER,
               first_name VARCHAR(256),
               last_name VARCHAR(256))');

    $db->exec('CREATE TABLE IF NOT EXISTS paper (
               id INTEGER PRIMARY KEY,
               user_id INTEGER,
               year INTEGER,
               title VARCHAR(256),
               author VARCHAR(256),
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
}
