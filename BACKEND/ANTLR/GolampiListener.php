<?php

/*
 * Generated from Golampi.g4 by ANTLR 4.13.2
 */

use Antlr\Antlr4\Runtime\Tree\ParseTreeListener;

/**
 * This interface defines a complete listener for a parse tree produced by
 * {@see GolampiParser}.
 */
interface GolampiListener extends ParseTreeListener {
	/**
	 * Enter a parse tree produced by {@see GolampiParser::program()}.
	 * @param $context The parse tree.
	 */
	public function enterProgram(Context\ProgramContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::program()}.
	 * @param $context The parse tree.
	 */
	public function exitProgram(Context\ProgramContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::declaration()}.
	 * @param $context The parse tree.
	 */
	public function enterDeclaration(Context\DeclarationContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::declaration()}.
	 * @param $context The parse tree.
	 */
	public function exitDeclaration(Context\DeclarationContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::varDecl()}.
	 * @param $context The parse tree.
	 */
	public function enterVarDecl(Context\VarDeclContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::varDecl()}.
	 * @param $context The parse tree.
	 */
	public function exitVarDecl(Context\VarDeclContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::constDecl()}.
	 * @param $context The parse tree.
	 */
	public function enterConstDecl(Context\ConstDeclContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::constDecl()}.
	 * @param $context The parse tree.
	 */
	public function exitConstDecl(Context\ConstDeclContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::functionDecl()}.
	 * @param $context The parse tree.
	 */
	public function enterFunctionDecl(Context\FunctionDeclContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::functionDecl()}.
	 * @param $context The parse tree.
	 */
	public function exitFunctionDecl(Context\FunctionDeclContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::parameters()}.
	 * @param $context The parse tree.
	 */
	public function enterParameters(Context\ParametersContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::parameters()}.
	 * @param $context The parse tree.
	 */
	public function exitParameters(Context\ParametersContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::parameter()}.
	 * @param $context The parse tree.
	 */
	public function enterParameter(Context\ParameterContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::parameter()}.
	 * @param $context The parse tree.
	 */
	public function exitParameter(Context\ParameterContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::pointer()}.
	 * @param $context The parse tree.
	 */
	public function enterPointer(Context\PointerContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::pointer()}.
	 * @param $context The parse tree.
	 */
	public function exitPointer(Context\PointerContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::returnType()}.
	 * @param $context The parse tree.
	 */
	public function enterReturnType(Context\ReturnTypeContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::returnType()}.
	 * @param $context The parse tree.
	 */
	public function exitReturnType(Context\ReturnTypeContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::typeList()}.
	 * @param $context The parse tree.
	 */
	public function enterTypeList(Context\TypeListContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::typeList()}.
	 * @param $context The parse tree.
	 */
	public function exitTypeList(Context\TypeListContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::type()}.
	 * @param $context The parse tree.
	 */
	public function enterType(Context\TypeContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::type()}.
	 * @param $context The parse tree.
	 */
	public function exitType(Context\TypeContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::primitiveType()}.
	 * @param $context The parse tree.
	 */
	public function enterPrimitiveType(Context\PrimitiveTypeContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::primitiveType()}.
	 * @param $context The parse tree.
	 */
	public function exitPrimitiveType(Context\PrimitiveTypeContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::arrayType()}.
	 * @param $context The parse tree.
	 */
	public function enterArrayType(Context\ArrayTypeContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::arrayType()}.
	 * @param $context The parse tree.
	 */
	public function exitArrayType(Context\ArrayTypeContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::statement()}.
	 * @param $context The parse tree.
	 */
	public function enterStatement(Context\StatementContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::statement()}.
	 * @param $context The parse tree.
	 */
	public function exitStatement(Context\StatementContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::block()}.
	 * @param $context The parse tree.
	 */
	public function enterBlock(Context\BlockContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::block()}.
	 * @param $context The parse tree.
	 */
	public function exitBlock(Context\BlockContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::shortVarDecl()}.
	 * @param $context The parse tree.
	 */
	public function enterShortVarDecl(Context\ShortVarDeclContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::shortVarDecl()}.
	 * @param $context The parse tree.
	 */
	public function exitShortVarDecl(Context\ShortVarDeclContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::assignment()}.
	 * @param $context The parse tree.
	 */
	public function enterAssignment(Context\AssignmentContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::assignment()}.
	 * @param $context The parse tree.
	 */
	public function exitAssignment(Context\AssignmentContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::assignOp()}.
	 * @param $context The parse tree.
	 */
	public function enterAssignOp(Context\AssignOpContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::assignOp()}.
	 * @param $context The parse tree.
	 */
	public function exitAssignOp(Context\AssignOpContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::exprStmt()}.
	 * @param $context The parse tree.
	 */
	public function enterExprStmt(Context\ExprStmtContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::exprStmt()}.
	 * @param $context The parse tree.
	 */
	public function exitExprStmt(Context\ExprStmtContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::ifStmt()}.
	 * @param $context The parse tree.
	 */
	public function enterIfStmt(Context\IfStmtContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::ifStmt()}.
	 * @param $context The parse tree.
	 */
	public function exitIfStmt(Context\IfStmtContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::switchStmt()}.
	 * @param $context The parse tree.
	 */
	public function enterSwitchStmt(Context\SwitchStmtContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::switchStmt()}.
	 * @param $context The parse tree.
	 */
	public function exitSwitchStmt(Context\SwitchStmtContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::caseClause()}.
	 * @param $context The parse tree.
	 */
	public function enterCaseClause(Context\CaseClauseContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::caseClause()}.
	 * @param $context The parse tree.
	 */
	public function exitCaseClause(Context\CaseClauseContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::defaultClause()}.
	 * @param $context The parse tree.
	 */
	public function enterDefaultClause(Context\DefaultClauseContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::defaultClause()}.
	 * @param $context The parse tree.
	 */
	public function exitDefaultClause(Context\DefaultClauseContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::forStmt()}.
	 * @param $context The parse tree.
	 */
	public function enterForStmt(Context\ForStmtContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::forStmt()}.
	 * @param $context The parse tree.
	 */
	public function exitForStmt(Context\ForStmtContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::forClause()}.
	 * @param $context The parse tree.
	 */
	public function enterForClause(Context\ForClauseContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::forClause()}.
	 * @param $context The parse tree.
	 */
	public function exitForClause(Context\ForClauseContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::forInit()}.
	 * @param $context The parse tree.
	 */
	public function enterForInit(Context\ForInitContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::forInit()}.
	 * @param $context The parse tree.
	 */
	public function exitForInit(Context\ForInitContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::breakStmt()}.
	 * @param $context The parse tree.
	 */
	public function enterBreakStmt(Context\BreakStmtContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::breakStmt()}.
	 * @param $context The parse tree.
	 */
	public function exitBreakStmt(Context\BreakStmtContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::continueStmt()}.
	 * @param $context The parse tree.
	 */
	public function enterContinueStmt(Context\ContinueStmtContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::continueStmt()}.
	 * @param $context The parse tree.
	 */
	public function exitContinueStmt(Context\ContinueStmtContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::returnStmt()}.
	 * @param $context The parse tree.
	 */
	public function enterReturnStmt(Context\ReturnStmtContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::returnStmt()}.
	 * @param $context The parse tree.
	 */
	public function exitReturnStmt(Context\ReturnStmtContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::expression()}.
	 * @param $context The parse tree.
	 */
	public function enterExpression(Context\ExpressionContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::expression()}.
	 * @param $context The parse tree.
	 */
	public function exitExpression(Context\ExpressionContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::logicalOr()}.
	 * @param $context The parse tree.
	 */
	public function enterLogicalOr(Context\LogicalOrContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::logicalOr()}.
	 * @param $context The parse tree.
	 */
	public function exitLogicalOr(Context\LogicalOrContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::logicalAnd()}.
	 * @param $context The parse tree.
	 */
	public function enterLogicalAnd(Context\LogicalAndContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::logicalAnd()}.
	 * @param $context The parse tree.
	 */
	public function exitLogicalAnd(Context\LogicalAndContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::equality()}.
	 * @param $context The parse tree.
	 */
	public function enterEquality(Context\EqualityContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::equality()}.
	 * @param $context The parse tree.
	 */
	public function exitEquality(Context\EqualityContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::relational()}.
	 * @param $context The parse tree.
	 */
	public function enterRelational(Context\RelationalContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::relational()}.
	 * @param $context The parse tree.
	 */
	public function exitRelational(Context\RelationalContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::additive()}.
	 * @param $context The parse tree.
	 */
	public function enterAdditive(Context\AdditiveContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::additive()}.
	 * @param $context The parse tree.
	 */
	public function exitAdditive(Context\AdditiveContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::multiplicative()}.
	 * @param $context The parse tree.
	 */
	public function enterMultiplicative(Context\MultiplicativeContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::multiplicative()}.
	 * @param $context The parse tree.
	 */
	public function exitMultiplicative(Context\MultiplicativeContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::unary()}.
	 * @param $context The parse tree.
	 */
	public function enterUnary(Context\UnaryContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::unary()}.
	 * @param $context The parse tree.
	 */
	public function exitUnary(Context\UnaryContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::postfix()}.
	 * @param $context The parse tree.
	 */
	public function enterPostfix(Context\PostfixContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::postfix()}.
	 * @param $context The parse tree.
	 */
	public function exitPostfix(Context\PostfixContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::incrementOp()}.
	 * @param $context The parse tree.
	 */
	public function enterIncrementOp(Context\IncrementOpContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::incrementOp()}.
	 * @param $context The parse tree.
	 */
	public function exitIncrementOp(Context\IncrementOpContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::index()}.
	 * @param $context The parse tree.
	 */
	public function enterIndex(Context\IndexContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::index()}.
	 * @param $context The parse tree.
	 */
	public function exitIndex(Context\IndexContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::call()}.
	 * @param $context The parse tree.
	 */
	public function enterCall(Context\CallContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::call()}.
	 * @param $context The parse tree.
	 */
	public function exitCall(Context\CallContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::primary()}.
	 * @param $context The parse tree.
	 */
	public function enterPrimary(Context\PrimaryContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::primary()}.
	 * @param $context The parse tree.
	 */
	public function exitPrimary(Context\PrimaryContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::arrayLiteral()}.
	 * @param $context The parse tree.
	 */
	public function enterArrayLiteral(Context\ArrayLiteralContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::arrayLiteral()}.
	 * @param $context The parse tree.
	 */
	public function exitArrayLiteral(Context\ArrayLiteralContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::arrayElements()}.
	 * @param $context The parse tree.
	 */
	public function enterArrayElements(Context\ArrayElementsContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::arrayElements()}.
	 * @param $context The parse tree.
	 */
	public function exitArrayElements(Context\ArrayElementsContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::arrayElement()}.
	 * @param $context The parse tree.
	 */
	public function enterArrayElement(Context\ArrayElementContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::arrayElement()}.
	 * @param $context The parse tree.
	 */
	public function exitArrayElement(Context\ArrayElementContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::builtinCall()}.
	 * @param $context The parse tree.
	 */
	public function enterBuiltinCall(Context\BuiltinCallContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::builtinCall()}.
	 * @param $context The parse tree.
	 */
	public function exitBuiltinCall(Context\BuiltinCallContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::exprList()}.
	 * @param $context The parse tree.
	 */
	public function enterExprList(Context\ExprListContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::exprList()}.
	 * @param $context The parse tree.
	 */
	public function exitExprList(Context\ExprListContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::idList()}.
	 * @param $context The parse tree.
	 */
	public function enterIdList(Context\IdListContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::idList()}.
	 * @param $context The parse tree.
	 */
	public function exitIdList(Context\IdListContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GolampiParser::literal()}.
	 * @param $context The parse tree.
	 */
	public function enterLiteral(Context\LiteralContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GolampiParser::literal()}.
	 * @param $context The parse tree.
	 */
	public function exitLiteral(Context\LiteralContext $context): void;
}