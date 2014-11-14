<?php

class Paper extends Model {
    public function authors() {
        return $this->has_many_through('Profile');
    }
}

class Profile extends Model {
    public function serialize() {
        return json_encode($this->as_array());
    }

    public function deserialize($json) {
        $fields = array('first_name', 'last_name', 'user_id', 'active');
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

class ProfilePaper extends Model {
}

class Role extends Model {
}

class User extends Model {
    public function serialize() {
        // TODO: exclude password and salt
        return json_encode(array('email' => $this->email));
    }

    public function deserialize($json) {
        $fields = array('email');
        foreach($fields as $field) {
            if (array_key_exists($field, $json)) {
                $this->$field = $json[$field];
            } else {
                throw new ModelException('couldn\'t find field \''.$field.'\'');
            }
        }
    }

    public function profile() {
        return $this->has_one('Profile');
    }
}
