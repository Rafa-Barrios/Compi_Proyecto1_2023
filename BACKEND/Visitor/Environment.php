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

    public function define($name, $value, $line=0, $column=0) {
        if (array_key_exists($name, $this->values) || array_key_exists($name, $this->constants)) {
            \ErrorTable::add("Semantico", "El identificador '$name' ya esta definido en este scope.", $line, $column);
            return;
        }
        $this->values[$name] = $value;
    }

    public function defineConst($name, $value, $line=0, $column=0) {
        if (array_key_exists($name, $this->values) || array_key_exists($name, $this->constants)) {
            \ErrorTable::add("Semantico", "El identificador '$name' ya esta definido en este scope.", $line, $column);
            return;
        }
        $this->constants[$name] = $value;
    }

    public function get($name, $line=0, $column=0) {
        if (array_key_exists($name, $this->values))   return $this->values[$name];
        if (array_key_exists($name, $this->constants)) return $this->constants[$name];
        if ($this->parent !== null) return $this->parent->get($name, $line, $column);
        \ErrorTable::add("Semantico", "Identificador '$name' no definido.", $line, $column);
        return null;
    }

    public function assign($name, $value, $line=0, $column=0) {
        if (array_key_exists($name, $this->constants)) {
            \ErrorTable::add("Semantico", "No se puede modificar la constante '$name'.", $line, $column);
            return;
        }
        if (array_key_exists($name, $this->values)) {
            $this->values[$name] = $value;
            return;
        }
        if ($this->parent !== null) {
            $this->parent->assign($name, $value, $line, $column);
            return;
        }
        \ErrorTable::add("Semantico", "Variable '$name' no definida.", $line, $column);
    }

    public function getEnvironmentOf($name) {
        if (array_key_exists($name, $this->values) || array_key_exists($name, $this->constants)) return $this;
        if ($this->parent !== null) return $this->parent->getEnvironmentOf($name);
        return null;
    }

    public function createChild() {
        return new Environment($this);
    }
}