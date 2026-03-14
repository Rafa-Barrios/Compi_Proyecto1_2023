grammar Golampi;

program
    : declaration* EOF
    ;

////////////////////
/// DECLARACIONES
////////////////////

declaration
    : varDecl
    | constDecl
    | functionDecl
    | statement
    ;

varDecl
    : 'var' idList type ('=' (exprList | arrayLiteral))?
    ;

constDecl
    : 'const' ID type '=' expression
    ;

functionDecl
    : 'func' ID '(' parameters? ')' returnType? block
    ;

parameters
    : parameter (',' parameter)*
    ;

parameter
    : pointer? ID type
    ;

pointer
    : '*'
    ;

returnType
    : type
    | '(' typeList ')'
    ;

typeList
    : type (',' type)*
    ;

////////////////////
/// TIPOS
////////////////////

type
    : primitiveType
    | arrayType
    ;

primitiveType
    : 'int32'
    | 'float32'
    | 'bool'
    | 'rune'
    | 'string'
    ;

arrayType
    : '[' expression ']' type
    ;

////////////////////
/// SENTENCIAS
////////////////////

statement
    : block
    | varDecl
    | constDecl
    | shortVarDecl
    | assignment
    | ifStmt
    | switchStmt
    | forStmt
    | breakStmt
    | continueStmt
    | returnStmt
    | exprStmt
    ;

block
    : '{' statement* '}'
    ;

shortVarDecl
    : idList ':=' exprList
    ;

assignment
    : expression assignOp expression
    ;

assignOp
    : '='
    | '+='
    | '-='
    | '*='
    | '/='
    ;

exprStmt
    : expression
    ;

////////////////////
/// CONTROL DE FLUJO
////////////////////

ifStmt
    : 'if' expression block ('else' (ifStmt | block))?
    ;

switchStmt
    : 'switch' expression '{' caseClause* defaultClause? '}'
    ;

caseClause
    : 'case' exprList ':' statement*
    ;

defaultClause
    : 'default' ':' statement*
    ;

forStmt
    : 'for' forClause block
    | 'for' expression block
    | 'for' block
    ;

forClause
    : forInit? ';' expression? ';' expression?
    ;

forInit
    : shortVarDecl
    | assignment
    ;

breakStmt
    : 'break'
    ;

continueStmt
    : 'continue'
    ;

returnStmt
    : 'return' exprList?
    ;

////////////////////
/// EXPRESIONES
////////////////////

expression
    : logicalOr
    ;

logicalOr
    : logicalAnd ('||' logicalAnd)*
    ;

logicalAnd
    : equality ('&&' equality)*
    ;

equality
    : relational (('==' | '!=') relational)*
    ;

relational
    : additive (('>' | '>=' | '<' | '<=') additive)*
    ;

additive
    : multiplicative (('+' | '-') multiplicative)*
    ;

multiplicative
    : unary (('*' | '/' | '%') unary)*
    ;

unary
    : ('!' | '-' | '&' | '*') unary
    | postfix
    ;

postfix
    : primary (index | call | incrementOp)*
    ;

incrementOp
    : '++'
    | '--'
    ;

index
    : '[' expression ']'
    ;

call
    : '(' exprList? ')'
    ;

primary
    : literal
    | ID
    | builtinCall
    | arrayLiteral
    | '(' expression ')'
    ;

////////////////////
/// ARRAY LITERAL
////////////////////

arrayLiteral
    : '[' expression ']' type '{' arrayElements? '}'
    ;

arrayElements
    : arrayElement (',' arrayElement)* ','?
    ;

arrayElement
    : expression
    | '{' exprList? '}'
    ;

////////////////////
/// FUNCIONES EMBEBIDAS
////////////////////

builtinCall
    : 'fmt' '.' 'Println' '(' exprList? ')'
    | 'len' '(' expression ')'
    | 'now' '(' ')'
    | 'substr' '(' expression ',' expression ',' expression ')'
    | 'typeOf' '(' expression ')'
    ;

////////////////////
/// LISTAS
////////////////////

exprList
    : expression (',' expression)*
    ;

idList
    : ID (',' ID)*
    ;

////////////////////
/// LITERALES
////////////////////

literal
    : INT
    | FLOAT
    | STRING
    | RUNE
    | 'true'
    | 'false'
    | 'nil'
    ;

////////////////////
/// TOKENS
////////////////////

ID
    : [a-zA-Z_] [a-zA-Z_0-9]*
    ;

INT
    : [0-9]+
    ;

FLOAT
    : [0-9]+ '.' [0-9]+
    ;

STRING
    : '"' (~["\\] | '\\' .)* '"'
    ;

RUNE
    : '\'' . '\''
    ;

LINE_COMMENT
    : '//' ~[\r\n]* -> skip
    ;

BLOCK_COMMENT
    : '/*' .*? '*/' -> skip
    ;

WS
    : [ \t\r\n]+ -> skip
    ;