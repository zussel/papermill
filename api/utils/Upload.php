<?php

class Upload
{

    private $file;
    private $destination;
    private $name;

    public function __construct($file, $destination, $name = null) {
        $this->file = $file;
        $this->destination = $destination;
        $this->name = ($name == null ? uniqid() : $name);
    }

    public function has_error() {
        return $this->file['error'] !== 0;
    }

    public function upload() {
        if ($this->has_error()) {
            return false;
        }
        if ($this->upload_file($this->file['tmp_name'], 'uploads/papers/' . $this->name) === true) {
            return array('url' => '/uploads/papers/' . $this->name, 'name' => $this->file['name']);
        } else {
            return false;
        }
    }

    private function upload_file($source, $destination) {
        return move_uploaded_file($source, $destination) === true;
    }
}