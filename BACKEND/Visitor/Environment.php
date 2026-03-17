<?php

namespace Visitor;

require_once __DIR__ . "/../Tablas/ErrorTable.php";

class Environment {

    private $values = [];
    private $constants = [];
    private $parent;

    public function __construct($parent = null) {
        $this->parent = $parent;
    }

    public function define($name, $value) {

        if (array_key_exists($name, $this->values) || array_key_exists($name, $this->constants)) {

            \ErrorTable::add(
                "Semantico",
                "El identificador '$name' ya esta definido en este scope.",
                0,
                0
            );

            return;
        }

        $this->values[$name] = $value;
    }

    public function defineConst($name, $value) {

        if (array_key_exists($name, $this->values) || array_key_exists($name, $this->constants)) {

            \ErrorTable::add(
                "Semantico",
                "El identificador '$name' ya esta definido en este scope.",
                0,
                0
            );

            return;
        }

        $this->constants[$name] = $value;
    }

    public function get($name) {

        if (array_key_exists($name, $this->values)) {
            return $this->values[$name];
        }

        if (array_key_exists($name, $this->constants)) {
            return $this->constants[$name];
        }

        if ($this->parent !== null) {
            return $this->parent->get($name);
        }

        \ErrorTable::add(
            "Semantico",
            "Identificador '$name' no definido.",
            0,
            0
        );

        return null;
    }

    public function assign($name, $value) {

        if (array_key_exists($name, $this->constants)) {

            \ErrorTable::add(
                "Semantico",
                "No se puede modificar la constante '$name'.",
                0,
                0
            );

            return;
        }

        if (array_key_exists($name, $this->values)) {
            $this->values[$name] = $value;
            return;
        }

        if ($this->parent !== null) {
            $this->parent->assign($name, $value);
            return;
        }

        \ErrorTable::add(
            "Semantico",
            "Variable '$name' no definida.",
            0,
            0
        );
    }

    public function getEnvironmentOf($name) {

        if (array_key_exists($name, $this->values) || array_key_exists($name, $this->constants)) {
            return $this;
        }

        if ($this->parent !== null) {
            return $this->parent->getEnvironmentOf($name);
        }

        return null;
    }

    public function createChild() {
        return new Environment($this);
    }
}