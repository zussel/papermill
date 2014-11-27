<?php

function paper_is_valid_json($json) {
    // validate data
    if (!isset($json['title'])) {
        return "missing title";
    } else if (!isset($json['year'])) {
        return "missing year";
    } else if (!isset($json['authors']) || !is_array($json['authors'])) {
        return "missing or invalid authors";
    } else if (!isset($json['tags']) || !is_array($json['tags'])) {
        return "missing or invalid tags";
    } else {
        return true;
    }
}

function paper_create($json) {
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

    return $paper;
}

function paper_update() {

}

function paper_add_author($paper, $json) {
    // new author insert on db
    $author = Model::factory('Profile')->create($json);
    // Todo: author controller to provide a create function
    $author->save();
    $author_paper = Model::factory('ProfilePaper')->create();
    $author_paper->profile_id = $author->id;
    $author_paper->paper_id = $paper->id;
    $author_paper->save();
    return $author;
}

function paper_remove_author() {

}

function paper_add_tag($paper, $json) {
    // new tag insert on db
    $tag = Model::factory('Tag')->create($json);
    $tag->save();
    $tag_paper = Model::factory('TagPaper')->create();
    $tag_paper->tag_id = $tag->id;
    $tag_paper->paper_id = $paper->id;
    $tag_paper->save();
    return $tag;
}

function paper_remove_tag() {

}
