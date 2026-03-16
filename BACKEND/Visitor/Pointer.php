<?php

namespace Visitor;

class Pointer {

    private $environment;
    private $name;

    public function __construct($environment, $name) {
        $this->environment = $environment;
        $this->name = $name;
    }

    public function get() {

        $value = $this->environment->get($this->name);

        if ($value instanceof Pointer) {
            return $value->get();
        }

        return $value;
    }

    public function set($value) {

        $current = $this->environment->get($this->name);

        if ($current instanceof Pointer) {
            $current->set($value);
            return;
        }

        $this->environment->assign($this->name, $value);
    }

}