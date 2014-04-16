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
               passwd CHAR(40),
               passwd_salt BLOB);');
//               passwd_salt CHAR(32));');

    $db->exec('CREATE TABLE IF NOT EXISTS author (
               id INTEGER PRIMARY KEY,
               user_id INTEGER,
               name VARCHAR(256),
               first_name VARCHAR(256),
               last_name VARCHAR(256),
               active INTEGER);');

    $db->exec('CREATE TABLE IF NOT EXISTS paper (
               id INTEGER PRIMARY KEY,
               user_id INTEGER,
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

    $db->exec('CREATE TABLE IF NOT EXISTS author_paper (
              author_id integer,
              paper_id integer);');
}

function clear_tables()
{
    $db = ORM::get_db();

    $db->exec('DELETE FROM user');
    $db->exec('DELETE FROM author');
    $db->exec('DELETE FROM paper');
    $db->exec('DELETE FROM tag');
    $db->exec('DELETE FROM paper_tag');
    $db->exec('DELETE FROM author_paper');
}

function drop_db() {
    $db = ORM::get_db();

    $db->exec('DROP TABLE user');
    $db->exec('DROP TABLE author');
    $db->exec('DROP TABLE paper');
    $db->exec('DROP TABLE tag');
    $db->exec('DROP TABLE paper_tag');
    $db->exec('DROP TABLE author_paper');
}