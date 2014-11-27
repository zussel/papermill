<?php

class Paper extends Model {
    public function authors() {
        return $this->has_many_through('Profile');
    }
    public function tags() {
        return $this->has_many_through('Tag');
    }
}

class Profile extends Model {
    public function papers() {
        return $this->has_many_through('Paper');
    }
}

class ProfilePaper extends Model {}

class Tag extends Model {}

class TagPaper extends Model {}

class TagUser extends Model {}

class Role extends Model {}

class UserRole extends Model {}

class User extends Model {
    public function profile() {
        return $this->has_one('Profile');
    }

    public function role() {
        return $this->has_one('Role');
    }
}
