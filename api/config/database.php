<?php
/**
 * Created by PhpStorm.
 * User: sascha
 * Date: 31.03.14
 * Time: 21:46
 */

function get_tables() {
    return array(
        'profile_paper',
        'paper_tag',
        'paper',
        'tag_user',
        'tag',
        'user_role',
        'role',
        'profile',
        'user'
    );
}

function setup_db() {
    $db = ORM::get_db();

    $db->exec('CREATE TABLE IF NOT EXISTS role (
               id INTEGER PRIMARY KEY,
               name VARCHAR(256));');
    /*
     * create user table consisting of
     * - unique id (id)
     * - email address (email)
     * - password (passwd)
     */
    $db->exec('CREATE TABLE IF NOT EXISTS user (
               id INTEGER PRIMARY KEY,
               email VARCHAR(256),
               passwd CHAR(40),
               passwd_salt BLOB);');
//               passwd_salt CHAR(32));');

    $db->exec('CREATE TABLE IF NOT EXISTS user_role (
              user_id integer,
              role_id integer);');


    $db->exec('CREATE TABLE IF NOT EXISTS profile (
               id INTEGER PRIMARY KEY,
               user_id INTEGER,
               first_name VARCHAR(256),
               last_name VARCHAR(256),
               active INTEGER);'
    );

    $db->exec('CREATE TABLE IF NOT EXISTS paper (
               id INTEGER PRIMARY KEY,
               created VARCHAR(32),
               year INTEGER,
               title VARCHAR(256),
               name VARCHAR(256),
               size INTEGER,
               type VARCHAR(16),
               extension VARCHAR(16),
               url VARCHAR(256));');

    $db->exec('CREATE TABLE IF NOT EXISTS paper_user (
              paper_id integer,
              user_id integer);');

    $db->exec('CREATE TABLE IF NOT EXISTS tag (
               id INTEGER PRIMARY KEY,
               name VARCHAR(256));');

    $db->exec('CREATE TABLE IF NOT EXISTS tag_user (
              tag_id integer,
              user_id integer);');

    $db->exec('CREATE TABLE IF NOT EXISTS paper_tag (
              paper_id integer,
              tag_id integer);');

    $db->exec('CREATE TABLE IF NOT EXISTS profile_paper (
              profile_id integer,
              paper_id integer);');
}

function clear_tables()
{
    $db = ORM::get_db();

    foreach(get_tables() as $table) {
        $db->exec('DELETE FROM '.$table);
    }
}

function drop_db() {
    $db = ORM::get_db();

    foreach(get_tables() as $table) {
        $db->exec('DROP TABLE '.$table);
    }
}