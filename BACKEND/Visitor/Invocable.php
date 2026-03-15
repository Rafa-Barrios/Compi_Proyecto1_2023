<?php

namespace Visitor;

interface Invocable
{
    public function arity();
    public function invoke($interpreter, $args);
}