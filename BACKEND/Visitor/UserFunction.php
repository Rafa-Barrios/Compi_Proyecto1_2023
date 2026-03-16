<?php

namespace Visitor;

use Visitor\Environment;
use Visitor\ReturnSignal;

class UserFunction implements Invocable {

    private $declaration;
    private $closure;

    public function __construct($declaration, $closure) {
        $this->declaration = $declaration;
        $this->closure = $closure;
    }

    /*
    ========================
    PARAMETER COUNT
    ========================
    */
    public function arity() {

        $params = $this->declaration->parameters();

        if ($params === null) {
            return 0;
        }

        return count($params->parameter());
    }

    /*
    ========================
    INVOKE FUNCTION
    ========================
    */
    public function invoke($interpreter, $args) {

        // crear nuevo scope basado en el closure
        $env = $this->closure->createChild();

        $params = $this->declaration->parameters();

        if ($params !== null) {

            $paramList = $params->parameter();

            for ($i = 0; $i < count($paramList); $i++) {

                $name = $paramList[$i]->ID()->getText();

                $value = $args[$i];

                // los punteros se pasan tal cual
                $env->define($name, $value);
            }
        }

        try {

            $interpreter->executeBlock(
                $this->declaration->block()->statement(),
                $env
            );

        } 
        catch (ReturnSignal $r) {

            // si hay múltiples valores
            if (is_array($r->value)) {
                return $r->value;
            }

            return $r->value;
        }

        // función sin return
        return null;
    }
}