<?php

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__ . '/../ANTLR/GolampiLexer.php';
require_once __DIR__ . '/../ANTLR/GolampiParser.php';
require_once __DIR__ . '/../ANTLR/GolampiVisitor.php';
require_once __DIR__ . '/../ANTLR/GolampiBaseVisitor.php';

require_once __DIR__ . '/../Visitor/Interpreter.php';

require_once __DIR__ . '/../Tablas/ErrorTable.php';
require_once __DIR__ . '/../Tablas/ErrorListener.php';

use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\CommonTokenStream;
use Visitor\Interpreter;

ErrorTable::clear();

//////////////////////////////
// DESCARGAR REPORTES
//////////////////////////////

if(isset($_GET["reporte"])){

    if($_GET["reporte"]=="errores"){

        $errors = ErrorTable::getErrors();

        echo "<html><body>";
        echo "<h2>Tabla de Errores</h2>";

        echo "<table border='1' style='border-collapse:collapse'>";

        echo "<tr>
                <th>#</th>
                <th>Tipo</th>
                <th>Descripcion</th>
                <th>Linea</th>
                <th>Columna</th>
              </tr>";

        foreach($errors as $e){

            echo "<tr>
                    <td>{$e->id}</td>
                    <td>{$e->type}</td>
                    <td>{$e->description}</td>
                    <td>{$e->line}</td>
                    <td>{$e->column}</td>
                  </tr>";

        }

        echo "</table>";
        echo "</body></html>";

        exit;
    }
}

//////////////////////////////
// EJECUCION NORMAL
//////////////////////////////

header("Content-Type: application/json");

$code = $_POST["code"] ?? "";

$input = InputStream::fromString($code);

$lexer = new GolampiLexer($input);
$lexer->removeErrorListeners();
$lexer->addErrorListener(new ErrorListener());
$tokens = new CommonTokenStream($lexer);
$parser = new GolampiParser($tokens);

$parser->removeErrorListeners();
$parser->addErrorListener(new ErrorListener());

$tree = $parser->program();

$visitor = new Interpreter();

try {

    $visitor->visit($tree);

} catch (\Throwable $e) {

    $msg = $e->getMessage();

    if($msg === null || $msg === ""){
        $msg = "Error semantico desconocido";
    }

    ErrorTable::add(
        "Semantico",
        $msg,
        0,
        0
    );

}

echo json_encode([
    "output" => $visitor->output,
    "errors" => ErrorTable::getErrors()
]);