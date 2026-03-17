<?php

require_once __DIR__ . "/ErrorEntry.php";

class ErrorTable {

    private static $errors = [];
    private static $counter = 1;

    public static function add($type, $description, $line, $column){

        // Evitar mensajes vacíos
        if ($description === null || $description === "") {
            $description = "Error semantico desconocido";
        }

        // Evitar null en linea y columna
        if ($line === null) {
            $line = 0;
        }

        if ($column === null) {
            $column = 0;
        }

        $error = new ErrorEntry(
            self::$counter++,
            $type,
            $description,
            $line,
            $column
        );

        self::$errors[] = $error;
    }

    public static function getErrors(){
        return array_values(self::$errors);
    }

    public static function hasErrors(){
        return !empty(self::$errors);
    }

    public static function clear(){
        self::$errors = [];
        self::$counter = 1;
    }

}