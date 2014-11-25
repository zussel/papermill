<?php

class PaperUpload
{
    public function __construct() {
    }

    public function upload($file, $destination, $name = null) {
        if ($this->has_error($file)) {
            return false;
        }
        $name = ($name === null ? uniqid() : $name);
        if ($this->upload_file($file['tmp_name'], $destination . $name) === true) {
            return array('url' => $destination . $name, 'name' => $file['name']);
        } else {
            return false;
        }
    }

    public function paper($request) {
        return $request->post('paper');
    }

    private function has_error($file) {
        return $file['error'] !== 0;
    }


    protected function upload_file($source, $destination) {
        return move_uploaded_file($source, $destination) === true;
    }
}