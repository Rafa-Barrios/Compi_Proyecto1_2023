<?php
use Antlr\Antlr4\Runtime\Recognizer;
use Antlr\Antlr4\Runtime\Lexer;
use Antlr\Antlr4\Runtime\Error\Listeners\BaseErrorListener;
use Antlr\Antlr4\Runtime\Error\Exceptions\RecognitionException;
require_once __DIR__ . "/ErrorTable.php";

class ErrorListener extends BaseErrorListener {
    public function syntaxError(
        Recognizer $recognizer,
        ?object $offendingSymbol,
        int $line,
        int $charPositionInLine,
        string $msg,
        ?RecognitionException $exception
    ): void {
        // Si el recognizer es el Lexer → error léxico
        // Si es el Parser → error sintáctico
        $type = ($recognizer instanceof Lexer) ? "Lexico" : "Sintactico";
        ErrorTable::add($type, $msg, $line, $charPositionInLine);
    }
}