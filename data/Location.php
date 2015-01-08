<?php

class Location {

    public $id;
    public $name;
    public $alias;
    public $description;
    public $map;
    public $images;
    public $is_suggested;
    public $likes;
    public $difficulty;
    public $length;
    public $county_name;

    public function __construct($id=NULL, $name=NULL, $alias=NULL, $description=NULL, 
            $map=NULL, $images=NULL, $is_suggested=NULL, $likes=NULL, $difficulty=NULL, 
            $length = NULL, $county_name=NULL) {
        $this->id = $id === NULL ? $this->id : $id;
        $this->name = $name === NULL ? $this->name : $name;
        $this->alias = $alias === NULL ? $this->alias : $alias;
        $this->description = $description === NULL ? $this->description : $description;
        $this->map = $map === NULL ? $this->map : $map;
        $this->images = $images === NULL ? $this->images : $images;
        $this->is_suggested = $is_suggested === NULL ? $this->is_suggested : $is_suggested;
        $this->likes = $likes === NULL ? $this->likes : $likes;
        $this->difficulty = $difficulty === NULL ? $this->difficulty : $difficulty;
        $this->length = $length === NULL ? $this->length : $length;
        $this->county_name = $county_name === NULL ? $this->county_name : $county_name;
    }

}

?>