<?php

/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 11/12/16
 * Time: 19:01
 */
class Image_Model {

    private $targetPath = "uploads/";

    public function __construct() {

    }

    public function storeImage($fileName, $tempFileName) {

        $this->targetPath = $this->targetPath . basename($fileName);

        if(move_uploaded_file($tempFileName, $this->targetPath)) {
            return $this->targetPath;
        } else{
            return "Error saving image";
        }
    }
}