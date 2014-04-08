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
        if (array_key_exists('title', $json)) {
            $this->title = $json['title'];
        } else {
            throw new ModelException('couldn\'t find field \'title\'');
        }
        if (array_key_exists('title', $json)) {
            $this->year = $json['year'];
        } else {
            throw new ModelException('couldn\'t find field \'year\'');
        }
        if (array_key_exists('title', $json)) {
            $this->author = $json['author'];
        } else {
            throw new ModelException('couldn\'t find field \'author\'');
        }
        if (array_key_exists('title', $json)) {
            $this->url = $json['url'];
        } else {
            throw new ModelException('couldn\'t find field \'url\'');
        }
    }
};

class User extends Model {
    public function serialize() {
        return json_encode($this->as_array());
    }

    public function deserialize($json) {
        if (array_key_exists('email', $json)) {
            $this->email = $json['email'];
        } else {
            throw new ModelException('couldn\'t find field \'email\'');
        }
        if (array_key_exists('passwd', $json)) {
            $this->passwd = $json['passwd'];
        } else {
            throw new ModelException('couldn\'t find field \'passwd\'');
        }
    }
}