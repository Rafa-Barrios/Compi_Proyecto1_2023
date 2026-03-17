<?php

class ErrorEntry {

    public $id;
    public $type;
    public $description;
    public $line;
    public $column;

    public function __construct($id,$type,$description,$line,$column){
        $this->id = $id;
        $this->type = $type;
        $this->description = $description;
        $this->line = $line;
        $this->column = $column;
    }

}