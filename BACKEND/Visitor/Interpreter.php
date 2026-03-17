<?php

namespace Visitor;

use GolampiBaseVisitor;

require_once "Environment.php";
require_once "FlowTypes.php";
require_once "Invocable.php";
require_once "UserFunction.php";
require_once "Pointer.php";
require_once __DIR__ . "/../Tablas/ErrorTable.php";
require_once __DIR__ . "/../Tablas/SymbolTable.php";

class Interpreter extends GolampiBaseVisitor
{
    public $output = "";

    private $environment;

    private $currentScope = "global";

    public function __construct()
    {
        $this->environment = new Environment();
    }

    /*
    ========================
    PROGRAM
    ========================
    */
    public function visitProgram($ctx)
    {
        foreach ($ctx->declaration() as $decl) {
            $this->visit($decl);
        }

        // ejecutar main si existe
        $mainEnv = $this->environment->getEnvironmentOf("main");
        if ($mainEnv === null) {
            \ErrorTable::add("Semantico", "El programa debe contener exactamente una funcion main.", 0, 0);
        } else {
            $main = $this->environment->get("main");
            if ($main instanceof Invocable) {
                $main->invoke($this, []);
            }
        }

        return $this->output;
    }

    /*
    ========================
    DECLARATION
    ========================
    */
    public function visitDeclaration($ctx)
    {
        if ($ctx->functionDecl()) {
            return $this->visit($ctx->functionDecl());
        }

        if ($ctx->varDecl()) {
            return $this->visit($ctx->varDecl());
        }

        if ($ctx->constDecl()) {
            return $this->visit($ctx->constDecl());
        }

        if ($ctx->statement()) {
            return $this->visit($ctx->statement());
        }

        return null;
    }

    /*
    ========================
    VARIABLE DECLARATION
    ========================
    */
   public function visitVarDecl($ctx)
    {
        $ids = $ctx->idList()->ID();
        $values = [];

        if ($ctx->exprList() !== null) {
            $values = $this->visit($ctx->exprList());
        }

        if ($ctx->arrayLiteral() !== null) {
            $values = [$this->visit($ctx->arrayLiteral())];
        }

        foreach ($ids as $i => $id) {

            $name = $id->getText();
            $value = null;

            // =========================
            // SI ES ARREGLO
            // =========================
            if ($ctx->type()->arrayType() !== null) {

                // obtener todas las dimensiones
                $sizes = $this->getArraySizes($ctx->type()->arrayType());

                if (isset($values[$i]) && is_array($values[$i])) {

                    // arreglo inicializado manualmente
                    $value = $values[$i];

                } else {

                    // crear arreglo multidimensional vacío
                    $value = $this->createMultiArray($sizes);
                }

            } else {

                // =========================
                // VARIABLE NORMAL
                // =========================

                if (isset($values[$i])) {
                    $value = $values[$i];
                }
            }

            $line   = $id->getSymbol()->getLine();
            $column = $id->getSymbol()->getCharPositionInLine();
            $this->environment->define($name, $value, $line, $column);
            \SymbolTable::add(
                $name,
                $this->resolveType($value),
                $this->currentScope,
                $this->formatValue($value),
                $line,
                $column
            );
        }

        return null;
    }

    /*
    ========================
    SHORT VARIABLE DECLARATION
    ========================
    */
    public function visitShortVarDecl($ctx)
    {
        $ids = $ctx->idList()->ID();
        $values = $this->visit($ctx->exprList());

        if (count($values) === 1 && is_array($values[0])) {
            $values = $values[0];
        }

        $hasNew = false;

        foreach ($ids as $i => $id) {

            $name = $id->getText();
            $value = $values[$i] ?? null;

            $env = $this->environment->getEnvironmentOf($name);

            if ($env !== null) {
                $env->assign($name, $value);
            } else {
                $line   = $id->getSymbol()->getLine();
                $column = $id->getSymbol()->getCharPositionInLine();
                $this->environment->define($name, $value, $line, $column);
                \SymbolTable::add(
                    $name,
                    $this->resolveType($value),
                    $this->currentScope,
                    $this->formatValue($value),
                    $line,
                    $column
                );
                $hasNew = true;
            }
        }

        if (!$hasNew) {

            \ErrorTable::add(
                "Semantico",
                "Short declaration requiere al menos una variable nueva.",
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine()
            );

        }

        return null;
    }

    /*
    ========================
    CONSTANT DECLARATION
    ========================
    */
    public function visitConstDecl($ctx)
    {
        $name = $ctx->ID()->getText();

        $value = $this->visit($ctx->expression());

        $line   = $ctx->getStart()->getLine();
        $column = $ctx->getStart()->getCharPositionInLine();
        $this->environment->defineConst($name, $value, $line, $column);
        \SymbolTable::add(
            $name,
            $this->resolveType($value),
            $this->currentScope,
            $this->formatValue($value),
            $line,
            $column
        );

        return $value;
    }

    /*
    ========================
    FUNCTION DECLARATION
    ========================
    */
    public function visitFunctionDecl($ctx)
    {
        $name = $ctx->ID()->getText();
        $line = $ctx->getStart()->getLine();
        $col  = $ctx->getStart()->getCharPositionInLine();

        // main no puede recibir parámetros
        if ($name === "main" && $ctx->parameters() !== null) {
            \ErrorTable::add("Semantico", "La funcion main no puede recibir parametros.", $line, $col);
        }

        // main no puede retornar valores
        if ($name === "main" && $ctx->returnType() !== null) {
            \ErrorTable::add("Semantico", "La funcion main no puede retornar valores.", $line, $col);
        }

        $function = new UserFunction($ctx, $this->environment);
        $this->environment->define($name, $function, $line, $col);
        \SymbolTable::add($name, "funcion", $this->currentScope, "—", $line, $col);

        $previousScope      = $this->currentScope;
        $this->currentScope = $name;
        // el cuerpo de la función se ejecutará cuando sea invocada
        $this->currentScope = $previousScope;

        return null;
    }

    /*
    ========================
    ASSIGNMENT
    ========================
    */
    public function visitAssignment($ctx)
    {
        $leftExpr = $ctx->expression(0);
        $leftText = $leftExpr->getText();

        $operator = $ctx->assignOp()->getText();
        $right = $this->visit($ctx->expression(1));

        /*
        =========================
        ASIGNACION A PUNTERO
        =========================
        */

        if ($leftExpr->getChildCount() == 2 && $leftExpr->getChild(0)->getText() == '*') {

            // obtener el objeto Pointer
            $pointer = $this->visit($leftExpr->getChild(1));

            if (!($pointer instanceof \Visitor\Pointer)) {

                \ErrorTable::add(
                    "Semantico",
                    "Asignacion a puntero invalida.",
                    $ctx->getStart()->getLine(),
                    $ctx->getStart()->getCharPositionInLine()
                );

                return null;
            }

            $left = $pointer->get();
            $result = null;

            switch ($operator) {

                case '=':
                    $result = $right;
                    break;

                case '+=':
                    $result = $this->add($left, $right);
                    break;

                case '-=':
                    $result = $this->sub($left, $right);
                    break;

                case '*=':
                    $result = $this->mul($left, $right);
                    break;

                case '/=':
                    $result = $this->div($left, $right);
                    break;
            }

            $pointer->set($result);

            return $result;
        }

        /*
        =========================
        ASIGNACION A ARRAY
        =========================
        */

        if (preg_match('/^([a-zA-Z_][a-zA-Z0-9_]*)/', $leftText, $nameMatch)) {

            $name = $nameMatch[1];

            preg_match_all('/\[(\d+)\]/', $leftText, $indexMatches);

            if (!empty($indexMatches[1])) {

                $indices = array_map('intval', $indexMatches[1]);

                $array = $this->environment->get($name);

                if (!is_array($array)) {

                    \ErrorTable::add(
                        "Semantico",
                        "La variable '$name' no es un arreglo.",
                        $ctx->getStart()->getLine(),
                        $ctx->getStart()->getCharPositionInLine()
                    );

                    return null;
                }

                $temp = &$array;

                foreach ($indices as $i => $index) {

                    if (!is_array($temp)) {

                        \ErrorTable::add(
                            "Semantico",
                            "Acceso invalido a dimension del arreglo.",
                            $ctx->getStart()->getLine(),
                            $ctx->getStart()->getCharPositionInLine()
                        );

                        return null;
                    }

                    if (!array_key_exists($index, $temp)) {

                        \ErrorTable::add(
                            "Semantico",
                            "Indice fuera de rango en arreglo.",
                            $ctx->getStart()->getLine(),
                            $ctx->getStart()->getCharPositionInLine()
                        );

                        return null;
                    }

                    if ($i === count($indices) - 1) {

                        $left = $temp[$index];
                        $result = null;

                        switch ($operator) {

                            case '=':
                                $result = $right;
                                break;

                            case '+=':
                                $result = $this->add($left, $right);
                                break;

                            case '-=':
                                $result = $this->sub($left, $right);
                                break;

                            case '*=':
                                $result = $this->mul($left, $right);
                                break;

                            case '/=':
                                $result = $this->div($left, $right);
                                break;
                        }

                        $temp[$index] = $result;

                    } else {

                        $temp = &$temp[$index];
                    }
                }

                $this->environment->assign($name, $array);

                return $result;
            }
        }

        /*
        =========================
        VARIABLE NORMAL
        =========================
        */

        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $leftText)) {

            \ErrorTable::add(
                "Semantico",
                "Asignacion invalida: $leftText",
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine()
            );

            return null;
        }

        $name = $leftText;

        $left = $this->environment->get($name);
        $result = null;

        switch ($operator) {

            case '=':
                $result = $right;
                break;

            case '+=':
                $result = $this->add($left, $right);
                break;

            case '-=':
                $result = $this->sub($left, $right);
                break;

            case '*=':
                $result = $this->mul($left, $right);
                break;

            case '/=':
                $result = $this->div($left, $right);
                break;
        }

        $this->environment->assign($name, $result);

        return $result;
    }
    
    /*
    ========================
    BLOCK
    ========================
    */
    public function visitBlock($ctx)
    {
        $previous = $this->environment;

        $this->environment = $previous->createChild();

        try {

            foreach ($ctx->statement() as $stmt) {
                $this->visit($stmt);
            }

        }
        catch (BreakSignal $e) {
            $this->environment = $previous;
            throw $e;
        }
        catch (ContinueSignal $e) {
            $this->environment = $previous;
            throw $e;
        }
        catch (ReturnSignal $e) {
            $this->environment = $previous;
            throw $e;
        }

        $this->environment = $previous;

        return null;
    }

    /*
    ========================
    IF STATEMENT
    ========================
    */
    public function visitIfStmt($ctx)
    {
        // evaluar condición
        $condition = $this->visit($ctx->expression());

        // la condición debe ser booleana
        if (!is_bool($condition)) {

            \ErrorTable::add(
                "Semantico",
                "La condicion del if debe ser booleana.",
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine()
            );

            return null;
        }

        // IF verdadero
        if ($condition) {
            return $this->visit($ctx->block(0));
        }

        // ELSE
        if ($ctx->getChildCount() > 3) {

            // else if
            if ($ctx->ifStmt()) {
                return $this->visit($ctx->ifStmt());
            }

            // else normal
            if ($ctx->block(1)) {
                return $this->visit($ctx->block(1));
            }
        }

        return null;
    }

    /*
    ========================
    SWITCH STATEMENT
    ========================
    */
    public function visitSwitchStmt($ctx)
    {
        $switchValue = $this->visit($ctx->expression());

        // recorrer cases
        foreach ($ctx->caseClause() as $case) {

            $values = $this->visit($case->exprList());

            foreach ($values as $value) {

                if ($switchValue == $value) {

                    try {

                        foreach ($case->statement() as $stmt) {
                            $this->visit($stmt);
                        }

                    }
                    catch (BreakSignal $e) {
                        return null;
                    }

                    return null;
                }
            }
        }

        // default
        if ($ctx->defaultClause()) {

            try {

                foreach ($ctx->defaultClause()->statement() as $stmt) {
                    $this->visit($stmt);
                }

            }
            catch (BreakSignal $e) {
                return null;
            }
        }

        return null;
    }

    /*
    ========================
    FOR STATEMENT
    ========================
    */
    public function visitForStmt($ctx)
    {
        // FOR con forClause
        if ($ctx->forClause()) {

            $clause = $ctx->forClause();

            // inicialización
            if ($clause->forInit()) {
                $this->visit($clause->forInit());
            }

            while (true) {

                // condición
                if ($clause->expression(0)) {

                    $cond = $this->visit($clause->expression(0));

                    if (!is_bool($cond)) {
                        \ErrorTable::add(
                            "Semantico",
                            "La condicion del for debe ser booleana.",
                            $ctx->getStart()->getLine(),
                            $ctx->getStart()->getCharPositionInLine()
                        );

                        break;
                    }

                    if (!$cond) {
                        break;
                    }
                }

                // cuerpo
                try {
                    $this->visit($ctx->block());
                }
                catch (ContinueSignal $e) {
                }
                catch (BreakSignal $e) {
                    break;
                }

                // update
                if ($clause->expression(1)) {
                    $this->visit($clause->expression(1));
                }
            }

            return null;
        }

        // FOR tipo while
        if ($ctx->expression()) {

            while (true) {

                $cond = $this->visit($ctx->expression());

                if (!is_bool($cond)) {
                    throw new \Exception("La condición del for debe ser booleana.");
                }

                if (!$cond) {
                    break;
                }

                try {
                    $this->visit($ctx->block());
                }
                catch (ContinueSignal $e) {
                    continue;
                }
                catch (BreakSignal $e) {
                    break;
                }
            }

            return null;
        }

        // FOR infinito
        while (true) {
            try {
                $this->visit($ctx->block());
            }
            catch (ContinueSignal $e) {
                continue;
            }
            catch (BreakSignal $e) {
                break;
            }
        }
        return null;
    }   

    /*
    ========================
    break
    ========================
    */
    public function visitBreakStmt($ctx)
    {
        throw new BreakSignal();
    }

    /*
    ========================
    continue
    ========================
    */
    public function visitContinueStmt($ctx)
    {
        throw new ContinueSignal();
    }

    /*
    ========================
    return
    ========================
    */
    public function visitReturnStmt($ctx)
    {
        $values = [];

        if ($ctx->exprList()) {

            foreach ($ctx->exprList()->expression() as $expr) {
                $values[] = $this->visit($expr);
            }

        }

        // si solo hay un valor, regresar valor simple
        if (count($values) === 1) {
            throw new ReturnSignal($values[0]);
        }

        // si hay múltiples valores
        throw new ReturnSignal($values);
    }

    /*
    ========================
    PRIMARY
    ========================
    */
    public function visitPrimary($ctx)
    {
        if ($ctx->literal() !== null) {
            return $this->visit($ctx->literal());
        }

        if ($ctx->builtinCall() !== null) {
            return $this->visit($ctx->builtinCall());
        }

        //  FALTA ESTE
        if ($ctx->arrayLiteral() !== null) {
            return $this->visit($ctx->arrayLiteral());
        }

        if ($ctx->ID() !== null) {
            $name = $ctx->ID()->getText();
            return $this->environment->get(
                $name,
                $ctx->ID()->getSymbol()->getLine(),
                $ctx->ID()->getSymbol()->getCharPositionInLine()
            );
        }

        if ($ctx->expression() !== null) {
            return $this->visit($ctx->expression());
        }

        return null;
    }

    /*
    ========================
    POSTFIX
    ========================
    */
    public function visitPostfix($ctx)
    {
        $value = $this->visit($ctx->primary());

        $varName = null;

        if ($ctx->primary()->ID()) {
            $varName = $ctx->primary()->ID()->getText();
        }

        /*
        ========================
        POSTFIX OPERATIONS
        ========================
        */

        for ($i = 1; $i < $ctx->getChildCount(); $i++) {

            $child = $ctx->getChild($i);

            /*
            ========================
            FUNCTION CALL
            ========================
            */

            if ($child instanceof \Context\CallContext) {

                // main no puede ser llamada explícitamente
                if ($varName === "main") {
                    \ErrorTable::add(
                        "Semantico",
                        "La funcion main no puede ser invocada explicitamente.",
                        $ctx->getStart()->getLine(),
                        $ctx->getStart()->getCharPositionInLine()
                    );
                    return null;
                }

                if (!($value instanceof Invocable)) {
                    \ErrorTable::add(
                        "Semantico",
                        "Intento de llamar algo que no es funcion.",
                        $ctx->getStart()->getLine(),
                        $ctx->getStart()->getCharPositionInLine()
                    );
                    return null;
                }

                $args = [];

                if ($child->exprList() !== null) {

                    foreach ($child->exprList()->expression() as $expr) {
                        $args[] = $this->visit($expr);
                    }

                }

                if (count($args) != $value->arity()) {

                    \ErrorTable::add(
                        "Semantico",
                        "Numero incorrecto de argumentos.",
                        $ctx->getStart()->getLine(),
                        $ctx->getStart()->getCharPositionInLine()
                    );

                    return null;
                }

                $value = $value->invoke($this, $args);
            }

            /*
            ========================
            ARRAY INDEX
            ========================
            */

            elseif ($child instanceof \Context\IndexContext) {

                $index = $this->visit($child->expression());

                if (!is_int($index)) {

                    \ErrorTable::add(
                        "Semantico",
                        "El indice debe ser entero.",
                        $ctx->getStart()->getLine(),
                        $ctx->getStart()->getCharPositionInLine()
                    );

                    return null;
                }

                if (!is_array($value)) {

                    \ErrorTable::add(
                        "Semantico",
                        "Intento de indexar algo que no es arreglo.",
                        $ctx->getStart()->getLine(),
                        $ctx->getStart()->getCharPositionInLine()
                    );

                    return null;
                }

                if (!array_key_exists($index, $value)) {

                    \ErrorTable::add(
                        "Semantico",
                        "Indice fuera de rango.",
                        $ctx->getStart()->getLine(),
                        $ctx->getStart()->getCharPositionInLine()
                    );

                    return null;
                }

                $value = $value[$index];
            }

            /*
            ========================
            ++ --
            ========================
            */

            elseif ($child instanceof \Context\IncrementOpContext) {

                $text = $child->getText();

                if ($text === "++") {
                    $value++;
                }

                if ($text === "--") {
                    $value--;
                }

                if ($varName !== null) {
                    $this->environment->assign($varName, $value);
                }
            }
        }

        return $value;
    }

    /*
    ========================
    LITERAL
    ========================
    */
    public function visitLiteral($ctx)
    {
        $text = $ctx->getText();

        // INT
        if (preg_match('/^-?\d+$/', $text)) {
            return intval($text);
        }

        // FLOAT
        if (preg_match('/^-?\d+\.\d+$/', $text)) {
            return floatval($text);
        }

        // STRING
        if ($text[0] === '"' && $text[strlen($text)-1] === '"') {
            return substr($text, 1, -1);
        }

        // RUNE
        if ($text[0] === "'" && $text[strlen($text)-1] === "'") {
            return substr($text, 1, -1);
        }

        if ($text === "true") return true;
        if ($text === "false") return false;
        if ($text === "nil") return null;

        return null;
    }

    /*
    ========================
    arrayLITERAL
    ========================
    */
    public function visitArrayLiteral($ctx)
    {
        $size = $this->visit($ctx->expression());

        $values = [];

        if ($ctx->arrayElements() !== null) {

            foreach ($ctx->arrayElements()->arrayElement() as $element) {

                // caso {1,2}
                if ($element->exprList() !== null) {

                    $values[] = $this->visit($element->exprList());

                }
                // caso expresión normal
                else {

                    $values[] = $this->visit($element->expression());

                }
            }
        }

        // sin inicialización → valores por defecto
        if (empty($values)) {
            return array_fill(0, $size, 0);
        }

        if (count($values) != $size) {

            \ErrorTable::add(
                "Semantico",
                "El tamaño del arreglo no coincide con la inicializacion.",
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine()
            );

            return null;
        }

        return $values;
    }

    /*
    ========================
    EXPRESSION
    ========================
    */
    public function visitExpression($ctx)
    {
        return $this->visit($ctx->logicalOr());
    }

    /*
    ========================
    ADDITIVE (+ -)
    ========================
    */
    public function visitAdditive($ctx)
    {
        $result = $this->visit($ctx->multiplicative(0));

        for ($i = 1; $i < count($ctx->multiplicative()); $i++) {

            $right = $this->visit($ctx->multiplicative($i));
            $op = $ctx->getChild(2*$i-1)->getText();

            if ($op == "+") {
                $result = $this->add($result, $right,
                    $ctx->getStart()->getLine(),
                    $ctx->getStart()->getCharPositionInLine()
                );

            } else {
                $result = $this->sub($result, $right,
                    $ctx->getStart()->getLine(),
                    $ctx->getStart()->getCharPositionInLine()
                );
            }
        }

        return $result;
    }

    /*
    ========================
    MULTIPLICATIVE (* / %)
    ========================
    */
    public function visitMultiplicative($ctx)
    {
        $result = $this->visit($ctx->unary(0));

        for ($i = 1; $i < count($ctx->unary()); $i++) {

            $right = $this->visit($ctx->unary($i));
            $op = $ctx->getChild(2*$i-1)->getText();

            switch ($op) {

                case "*":
                    $result = $this->mul($result, $right, $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine());
                    break;

                case "/":
                    $result = $this->div($result, $right, $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine());
                    break;

                case "%":
                    $result = $this->mod($result, $right, $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine());
                    break;
            }
        }

        return $result;
    }

    /*
    ========================
    UNARY (-)
    ========================
    */
    public function visitUnary($ctx)
    {
        // caso: operador unario
        if ($ctx->getChildCount() == 2) {

            $op = $ctx->getChild(0)->getText();

            switch ($op) {

                case '!':
                    $value = $this->visit($ctx->unary());

                    if (!is_bool($value)) return null;

                    return !$value;

                case '-':
                    $value = $this->visit($ctx->unary());

                    if (is_int($value) || is_float($value)) {
                        return -$value;
                    }

                    if ($this->isRune($value)) {
                        return -ord($value);
                    }

                    return null;

                // =========================
                // OPERADOR &
                // =========================
                case '&':

                    $node = $ctx->unary();

                    $name = $node->getText();

                    if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $name)) {

                        \ErrorTable::add(
                            "Semantico",
                            "El operador & solo puede aplicarse a variables.",
                            $ctx->getStart()->getLine(),
                            $ctx->getStart()->getCharPositionInLine()
                        );

                        return null;
                    }

                    $env = $this->environment->getEnvironmentOf($name);

                    return new \Visitor\Pointer($env, $name);

                // =========================
                // OPERADOR *
                // =========================
                case '*':

                    $value = $this->visit($ctx->unary());

                    if ($value instanceof \Visitor\Pointer) {
                        return $value->get();
                    }

                    \ErrorTable::add(
                        "Semantico",
                        "No es un puntero valido.",
                        $ctx->getStart()->getLine(),
                        $ctx->getStart()->getCharPositionInLine()
                    );

                    return null;
            }
        }

        // caso normal → pasar a postfix
        return $this->visit($ctx->postfix());
    }

    /*
    ========================
    EQUALITY (== !=)
    ========================
    */
    public function visitEquality($ctx)
    {
        $result = $this->visit($ctx->relational(0));

        for ($i = 1; $i < count($ctx->relational()); $i++) {

            $right = $this->visit($ctx->relational($i));
            $op = $ctx->getChild(2*$i-1)->getText();

            if ($op == "==") {
                $result = $this->equal($result, $right);
            } else {
                $result = $this->notEqual($result, $right);
            }
        }

        return $result;
    }

    /*
    ========================
    realcional (> < >= <=)
    ========================
    */
    public function visitRelational($ctx)
    {
        $result = $this->visit($ctx->additive(0));

        for ($i = 1; $i < count($ctx->additive()); $i++) {

            $right = $this->visit($ctx->additive($i));
            $op = $ctx->getChild(2*$i-1)->getText();

            switch ($op) {

                case ">":
                    $result = $this->greater($result, $right, 
                    $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine());
                    break;

                case ">=":
                    $result = $this->greaterEqual($result, $right,
                    $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine());
                    break;

                case "<":
                    $result = $this->less($result, $right, 
                    $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine());
                    break;

                case "<=":
                    $result = $this->lessEqual($result, $right, 
                    $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine());
                    break;
            }
        }

        return $result;
    }

    /*
    ========================
    LOGICAL AND (&&)
    ========================
    */
    public function visitLogicalAnd($ctx)
    {
        $count = count($ctx->equality());

        $result = $this->visit($ctx->equality(0));

        // si no hay && devolvemos el valor
        if ($count == 1) {
            return $result;
        }

        if (!is_bool($result)) {
            return null;
        }

        for ($i = 1; $i < $count; $i++) {

            if ($result === false) {
                return false;
            }

            $right = $this->visit($ctx->equality($i));

            if (!is_bool($right)) {
                return null;
            }

            $result = $result && $right;
        }

        return $result;
    }

    /*
    ========================
    LOGICAL OR (||)
    ========================
    */
    public function visitLogicalOr($ctx)
    {
        $count = count($ctx->logicalAnd());

        $result = $this->visit($ctx->logicalAnd(0));

        // si no hay || simplemente devolvemos el valor
        if ($count == 1) {
            return $result;
        }

        if (!is_bool($result)) {
            return null;
        }

        for ($i = 1; $i < $count; $i++) {

            if ($result === true) {
                return true;
            }

            $right = $this->visit($ctx->logicalAnd($i));

            if (!is_bool($right)) {
                return null;
            }

            $result = $result || $right;
        }

        return $result;
    }

    /*
    ========================
    EXPRESSION LIST
    ========================
    */
    public function visitExprList($ctx)
    {
        $values = [];

        foreach ($ctx->expression() as $expr) {
            $values[] = $this->visit($expr);
        }

        return $values;
    }

    /*
    ========================
    BUILTIN CALLS
    ========================
    */
    public function visitBuiltinCall($ctx)
    {
        $text = $ctx->getText();

        // ========================
        // fmt.Println
        // ========================
        if (str_starts_with($text, "fmt.Println")) {

            $values = [];

            if ($ctx->exprList()) {
                foreach ($ctx->exprList()->expression() as $expr) {
                    $values[] = $this->visit($expr);
                }
            }

            foreach ($values as &$v) {

                if ($v === null) {
                    $v = "nil";
                }
                elseif (is_bool($v)) {
                    $v = $v ? "true" : "false";
                }
                elseif (is_array($v)) {
                    $v = json_encode($v);
                }
                elseif (is_object($v)) {

                    if ($v instanceof \Visitor\UserFunction) {
                        $v = "<function>";
                    } else {
                        $v = "<object>";
                    }

                }
                else {
                    $v = (string)$v;
                }
            }

            $this->output .= implode(" ", $values) . "\n";

            return null;
        }

        // ========================
        // len()
        // ========================
        if (str_starts_with($text, "len")) {

            $exprs = $ctx->expression();
            $value = $this->visit($exprs[0]);

            // strings
            if (is_string($value)) {
                return strlen($value);
            }

            // arrays o matrices
            if (is_array($value)) {
                return count($value);
            }

            \ErrorTable::add(
                "Semantico",
                "len() solo funciona con strings o arreglos.",
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine()
            );

            return null;
        }

        // ========================
        // now()
        // ========================
        if (str_starts_with($text, "now")) {
            date_default_timezone_set("America/Guatemala");
            return date("Y-m-d H:i:s");
        }

        // ========================
        // substr()
        // ========================
        if (str_starts_with($text, "substr")) {

            $exprs = $ctx->expression();

            if (count($exprs) != 3) {
                \ErrorTable::add(
                    "Semantico",
                    "substr() requiere 3 argumentos.",
                    $ctx->getStart()->getLine(),
                    $ctx->getStart()->getCharPositionInLine()
                );

                return null;
            }

            $str = $this->visit($exprs[0]);
            $start = $this->visit($exprs[1]);
            $length = $this->visit($exprs[2]);

            if (!is_string($str)) {
                \ErrorTable::add(
                    "Semantico",
                    "substr() requiere un string.",
                    $ctx->getStart()->getLine(),
                    $ctx->getStart()->getCharPositionInLine()
                );

                return null;
            }

            if (!is_int($start) || !is_int($length)) {
                \ErrorTable::add(
                    "Semantico",
                    "substr() requiere indices enteros.",
                    $ctx->getStart()->getLine(),
                    $ctx->getStart()->getCharPositionInLine()
                );

                return null;
            }

            if ($start < 0 || $length < 0 || $start + $length > strlen($str)) {
                \ErrorTable::add(
                    "Semantico",
                    "Indices invalidos en substr().",
                    $ctx->getStart()->getLine(),
                    $ctx->getStart()->getCharPositionInLine()
                );

                return null;
            }

            return substr($str, $start, $length);
        }

        // ========================
        // typeOf()
        // ========================
        if (str_starts_with($text, "typeOf")) {

            $exprs = $ctx->expression();
            $value = $this->visit($exprs[0]);

            if (is_int($value)) {
                return "int32";
            }

            if (is_float($value)) {
                return "float32";
            }

            if (is_bool($value)) {
                return "bool";
            }

            if (is_string($value)) {

                if ($this->isRune($value)) {
                    return "rune";
                }

                return "string";
            }

            if (is_array($value)) {
                return "array";
            }

            if ($value === null) {
                return "nil";
            }

            return "unknown";
        }

        return null;
    }

    /*
    ========================
    EXPRESSION STATEMENT
    ========================
    */
    public function visitExprStmt($ctx)
    {
        return $this->visit($ctx->expression());
    }

    /*
    ========================
    HELPERS
    ========================
    */

    public function executeBlock($statements, $environment)
    {
        $previous = $this->environment;

        try {

            $this->environment = $environment;

            foreach ($statements as $stmt) {
                $this->visit($stmt);
            }

        } finally {

            $this->environment = $previous;
        }
    }

    private function isRune($value)
    {
        return is_string($value) && strlen($value) === 1;
    }

    private function getArraySizes($arrayType)
    {
        $sizes = [];

        while ($arrayType !== null) {

            // tamaño de esta dimensión
            $size = $this->visit($arrayType->expression());

            if (!is_int($size)) {
                \ErrorTable::add(
                    "Semantico",
                    "El tamaño del arreglo debe ser entero.",
                    $arrayType->getStart()->getLine(),
                    $arrayType->getStart()->getCharPositionInLine()
                );

                return null;
            }

            $sizes[] = $size;

            // revisar el tipo interno
            $typeCtx = $arrayType->type();

            if ($typeCtx !== null) {

                // buscar si el type contiene otro arrayType
                $nextArray = $typeCtx->arrayType();

                if ($nextArray !== null) {
                    $arrayType = $nextArray;
                } else {
                    $arrayType = null;
                }

            } else {
                $arrayType = null;
            }
        }

        return $sizes;
    }
    
    private function createMultiArray($sizes)
    {
        $size = array_shift($sizes);

        if (empty($sizes)) {
            return array_fill(0, $size, 0);
        }

        $array = [];

        for ($i = 0; $i < $size; $i++) {
            $array[] = $this->createMultiArray($sizes);
        }

        return $array;
    }

    private function add($a, $b, $line=0, $column=0)
    {
        if ($a === null || $b === null) return null;

        if (is_bool($a) || is_bool($b)) {
            \ErrorTable::add("Semantico", "Tipos incompatibles en operacion +.", $line, $column);
            return null;
        }

        if (is_string($a) && is_string($b)) {
            return $a . $b;
        }

        if ($this->isRune($a)) $a = ord($a);
        if ($this->isRune($b)) $b = ord($b);

        if (is_numeric($a) && is_numeric($b)) {

            if (is_float($a) || is_float($b)) {
                return floatval($a) + floatval($b);
            }

            return $a + $b;
        }
        
        \ErrorTable::add("Semantico", "Tipos incompatibles en operacion +.", $line, $column);

        return null;
    }

    private function sub($a, $b, $line=0, $column=0)
    {
        if ($a === null || $b === null) return null;

        if (is_bool($a) || is_bool($b)) {
            \ErrorTable::add("Semantico", "Tipos incompatibles en operacion -.", $line, $column);
            return null;
        }

        if ($this->isRune($a)) $a = ord($a);
        if ($this->isRune($b)) $b = ord($b);

        if (is_numeric($a) && is_numeric($b)) {

            if (is_float($a) || is_float($b)) {
                return floatval($a) - floatval($b);
            }

            return $a - $b;
        }

        \ErrorTable::add("Semantico", "Tipos incompatibles en operacion -.", $line, $column);

        return null;
    }

    private function mul($a, $b, $line=0, $column=0)
    {
        if ($a === null || $b === null) return null;

        if (is_bool($a) || is_bool($b)) {
            \ErrorTable::add("Semantico", "Tipos incompatibles en operacion *.", $line, $column);
            return null;
        }

        if (is_string($a) && is_int($b)) {
            return str_repeat($a, $b);
        }

        if (is_string($b) && is_int($a)) {
            return str_repeat($b, $a);
        }

        if ($this->isRune($a)) $a = ord($a);
        if ($this->isRune($b)) $b = ord($b);

        if (is_numeric($a) && is_numeric($b)) {

            if (is_float($a) || is_float($b)) {
                return floatval($a) * floatval($b);
            }

            return $a * $b;
        }

        \ErrorTable::add("Semantico", "Tipos incompatibles en operacion *.", $line, $column);

        return null;
    }

    private function div($a, $b, $line=0, $column=0)
    {
        if ($a === null || $b === null) return null;

        if (is_bool($a) || is_bool($b)) {
            \ErrorTable::add("Semantico", "Tipos incompatibles en operacion /.", $line, $column);
            return null;
        }

        if ($this->isRune($a)) $a = ord($a);
        if ($this->isRune($b)) $b = ord($b);

        if ($b == 0) {

            \ErrorTable::add(
                "Semantico",
                "Division por cero.",
                $line,
                $column
            );

            return null;
        }

        if (is_numeric($a) && is_numeric($b)) {

            if (is_float($a) || is_float($b)) {
                return floatval($a) / floatval($b);
            }

            return intdiv($a, $b);
        }

        \ErrorTable::add("Semantico", "Tipos incompatibles en operacion /.", $line, $column);

        return null;
    }

    private function mod($a, $b, $line=0, $column=0)
    {
        if ($a === null || $b === null) return null;

        if (is_bool($a) || is_bool($b)) {
            \ErrorTable::add("Semantico", "Tipos incompatibles en operacion %.", $line, $column);
            return null;
        }

        if ($this->isRune($a)) $a = ord($a);
        if ($this->isRune($b)) $b = ord($b);

        if (is_int($a) && is_int($b)) {
            return $a % $b;
        }

        \ErrorTable::add("Semantico", "Tipos incompatibles en operacion %.", $line, $column);

        return null;
    }

    private function equal($a, $b)
    {
        if ($a === null || $b === null) return null;

        if ($this->isRune($a)) $a = ord($a);
        if ($this->isRune($b)) $b = ord($b);

        if (is_bool($a) && is_bool($b)) {
            return $a === $b;
        }

        if (is_string($a) && is_string($b)) {
            return $a === $b;
        }

        if (is_numeric($a) && is_numeric($b)) {
            return $a == $b;
        }

        return null;
    }

    private function notEqual($a, $b)
    {
        $result = $this->equal($a, $b);

        if ($result === null) return null;

        return !$result;
    }

    private function greater($a, $b, $line=0, $column=0)
    {
        if ($a === null || $b === null) return null;

        if (is_bool($a) || is_bool($b)) {
            \ErrorTable::add("Semantico", "Tipos incompatibles en operacion >.", $line, $column);
            return null;
        }

        if ($this->isRune($a)) $a = ord($a);
        if ($this->isRune($b)) $b = ord($b);

        if (is_string($a) && is_string($b)) {
            return $a > $b;
        }

        if (is_numeric($a) && is_numeric($b)) {
            return $a > $b;
        }

        \ErrorTable::add("Semantico", "Tipos incompatibles en operacion >.", $line, $column);

        return null;
    }

    private function greaterEqual($a, $b, $line=0, $column=0)
    {
        if ($a === null || $b === null) return null;

        if (is_bool($a) || is_bool($b)) {
            \ErrorTable::add("Semantico", "Tipos incompatibles en operacion >=.", $line, $column);
            return null;
        }

        if ($this->isRune($a)) $a = ord($a);
        if ($this->isRune($b)) $b = ord($b);

        if (is_string($a) && is_string($b)) {
            return $a >= $b;
        }

        if (is_numeric($a) && is_numeric($b)) {
            return $a >= $b;
        }

        \ErrorTable::add("Semantico", "Tipos incompatibles en operacion >=.", $line, $column);

        return null;
    }

    private function less($a, $b, $line=0, $column=0)
    {
        if ($a === null || $b === null) return null;

        if (is_bool($a) || is_bool($b)) {
            \ErrorTable::add("Semantico", "Tipos incompatibles en operacion <.", $line, $column);
            return null;
        }

        if ($this->isRune($a)) $a = ord($a);
        if ($this->isRune($b)) $b = ord($b);

        if (is_string($a) && is_string($b)) {
            return $a < $b;
        }

        if (is_numeric($a) && is_numeric($b)) {
            return $a < $b;
        }

        \ErrorTable::add("Semantico", "Tipos incompatibles en operacion <.", $line, $column);

        return null;
    }

    private function lessEqual($a, $b, $line=0, $column=0)
    {
        if ($a === null || $b === null) return null;

        if (is_bool($a) || is_bool($b)) {
            \ErrorTable::add("Semantico", "Tipos incompatibles en operacion <=.", $line, $column);
            return null;
        }

        if ($this->isRune($a)) $a = ord($a);
        if ($this->isRune($b)) $b = ord($b);

        if (is_string($a) && is_string($b)) {
            return $a <= $b;
        }

        if (is_numeric($a) && is_numeric($b)) {
            return $a <= $b;
        }

        \ErrorTable::add("Semantico", "Tipos incompatibles en operacion <=.", $line, $column);

        return null;
    }

    private function resolveType($value) {
        if ($value instanceof \Visitor\UserFunction) return "funcion";
        if (is_int($value))   return "entero";
        if (is_float($value)) return "decimal";
        if (is_bool($value))  return "booleano";
        if (is_array($value)) return "arreglo";
        if (is_string($value)) {
            if ($this->isRune($value)) return "caracter";
            return "cadena";
        }
        return "desconocido";
    }

    private function formatValue($value) {
        if ($value === null)  return "—";
        if (is_bool($value))  return $value ? "true" : "false";
        if (is_array($value)) return json_encode($value);
        if ($value instanceof \Visitor\UserFunction) return "—";
        return (string)$value;
    }
}