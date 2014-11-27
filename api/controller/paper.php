<?php

function create_paper($json) {
    $paper = Model::factory('Paper')->create();

    $paper->title = $json['title'];
    $paper->year = $json['year'];
    $paper->size = $json['size'];
    $paper->url = $json['url'];
    $paper->filename = $json['name'];
    $now = date('now');
    $paper->created = $now;
    $paper->updated = $now;

    $paper->save();
}

function update_paper() {

}

function add_author() {

}

function remove_author() {

}

function add_tag() {

}

function remove_tag() {

}
