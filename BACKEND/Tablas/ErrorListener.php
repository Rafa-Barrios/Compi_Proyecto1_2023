<?php

use Antlr\Antlr4\Runtime\Recognizer;
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

        ErrorTable::add(
            "Sintactico",
            $msg,
            $line,
            $charPositionInLine
        );

    }

}