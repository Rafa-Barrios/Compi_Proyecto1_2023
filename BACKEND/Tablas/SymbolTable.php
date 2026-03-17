<?php
require_once __DIR__ . "/SymbolEntry.php";

class SymbolTable {
    private static $symbols = [];

    public static function add($identifier, $type, $scope, $value, $line, $column) {
        self::$symbols[] = new SymbolEntry($identifier, $type, $scope, $value, $line, $column);
    }

    public static function getSymbols() {
        return array_values(self::$symbols);
    }

    public static function clear() {
        self::$symbols = [];
    }
}