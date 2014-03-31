<?php

class Paper extends Model {
    
    /**
     * serialize to a json object
     */
    public function serialize() {
        $arr = $this->as_array();
        $arr["year"] = intval($arr["year"]);
        return json_encode($arr);
    }
    /**
     * deserialze from a json object
     */
    public function deserialize($json) {
        $this->title = $json['title'];
        $this->year = $json['year'];
        $this->author = $json['author'];
        $this->url = $json['url'];
    }
};
