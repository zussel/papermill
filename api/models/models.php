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
        $fields = array('title', 'year', 'author');
        foreach($fields as $field) {
            if (array_key_exists($field, $json)) {
                $this->$field = $json[$field];
            } else {
                throw new ModelException('couldn\'t find field \''.$field.'\'');
            }
        }
    }

    public function authors() {
        return $this->has_many_through('Author');
    }
}

class Author extends Model {
    public function serialize() {
        return json_encode($this->as_array());
    }

    public function deserialize($json) {
        $fields = array('name', 'user_id');
        foreach($fields as $field) {
            if (array_key_exists($field, $json)) {
                $this->$field = $json[$field];
            } else {
                throw new ModelException('couldn\'t find field \''.$field.'\'');
            }
        }
    }

    public function papers() {
        return $this->has_many_through('Paper');
    }
}

class AuthorPaper extends Model {
}

class User extends Model {
    public function serialize() {
        return json_encode($this->as_array());
    }

    public function deserialize($json) {
        $fields = array('email', 'passwd');
        foreach($fields as $field) {
            if (array_key_exists($field, $json)) {
                $this->$field = $json[$field];
            } else {
                throw new ModelException('couldn\'t find field \''.$field.'\'');
            }
        }
    }

    public function author() {
        return $this->has_one('Author');
    }
}