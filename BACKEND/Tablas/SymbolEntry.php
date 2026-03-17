<?php
class SymbolEntry {
    public $identifier;
    public $type;
    public $scope;
    public $value;
    public $line;
    public $column;

    public function __construct($identifier, $type, $scope, $value, $line, $column) {
        $this->identifier = $identifier;
        $this->type       = $type;
        $this->scope      = $scope;
        $this->value      = $value;
        $this->line       = $line;
        $this->column     = $column;
    }
}