<?php

/*
 * Generated from Golampi.g4 by ANTLR 4.13.2
 */

use Antlr\Antlr4\Runtime\Tree\ParseTreeVisitor;

/**
 * This interface defines a complete generic visitor for a parse tree produced by {@see GolampiParser}.
 */
interface GolampiVisitor extends ParseTreeVisitor
{
	/**
	 * Visit a parse tree produced by {@see GolampiParser::program()}.
	 *
	 * @param Context\ProgramContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitProgram(Context\ProgramContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::declaration()}.
	 *
	 * @param Context\DeclarationContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitDeclaration(Context\DeclarationContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::varDecl()}.
	 *
	 * @param Context\VarDeclContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitVarDecl(Context\VarDeclContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::constDecl()}.
	 *
	 * @param Context\ConstDeclContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitConstDecl(Context\ConstDeclContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::functionDecl()}.
	 *
	 * @param Context\FunctionDeclContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitFunctionDecl(Context\FunctionDeclContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::parameters()}.
	 *
	 * @param Context\ParametersContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitParameters(Context\ParametersContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::parameter()}.
	 *
	 * @param Context\ParameterContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitParameter(Context\ParameterContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::pointer()}.
	 *
	 * @param Context\PointerContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitPointer(Context\PointerContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::returnType()}.
	 *
	 * @param Context\ReturnTypeContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitReturnType(Context\ReturnTypeContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::typeList()}.
	 *
	 * @param Context\TypeListContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitTypeList(Context\TypeListContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::type()}.
	 *
	 * @param Context\TypeContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitType(Context\TypeContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::primitiveType()}.
	 *
	 * @param Context\PrimitiveTypeContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitPrimitiveType(Context\PrimitiveTypeContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::arrayType()}.
	 *
	 * @param Context\ArrayTypeContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitArrayType(Context\ArrayTypeContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::statement()}.
	 *
	 * @param Context\StatementContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitStatement(Context\StatementContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::block()}.
	 *
	 * @param Context\BlockContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitBlock(Context\BlockContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::shortVarDecl()}.
	 *
	 * @param Context\ShortVarDeclContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitShortVarDecl(Context\ShortVarDeclContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::assignment()}.
	 *
	 * @param Context\AssignmentContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitAssignment(Context\AssignmentContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::assignOp()}.
	 *
	 * @param Context\AssignOpContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitAssignOp(Context\AssignOpContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::exprStmt()}.
	 *
	 * @param Context\ExprStmtContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExprStmt(Context\ExprStmtContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::ifStmt()}.
	 *
	 * @param Context\IfStmtContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitIfStmt(Context\IfStmtContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::switchStmt()}.
	 *
	 * @param Context\SwitchStmtContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitSwitchStmt(Context\SwitchStmtContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::caseClause()}.
	 *
	 * @param Context\CaseClauseContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitCaseClause(Context\CaseClauseContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::defaultClause()}.
	 *
	 * @param Context\DefaultClauseContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitDefaultClause(Context\DefaultClauseContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::forStmt()}.
	 *
	 * @param Context\ForStmtContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitForStmt(Context\ForStmtContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::forClause()}.
	 *
	 * @param Context\ForClauseContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitForClause(Context\ForClauseContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::forInit()}.
	 *
	 * @param Context\ForInitContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitForInit(Context\ForInitContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::breakStmt()}.
	 *
	 * @param Context\BreakStmtContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitBreakStmt(Context\BreakStmtContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::continueStmt()}.
	 *
	 * @param Context\ContinueStmtContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitContinueStmt(Context\ContinueStmtContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::returnStmt()}.
	 *
	 * @param Context\ReturnStmtContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitReturnStmt(Context\ReturnStmtContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::expression()}.
	 *
	 * @param Context\ExpressionContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExpression(Context\ExpressionContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::logicalOr()}.
	 *
	 * @param Context\LogicalOrContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitLogicalOr(Context\LogicalOrContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::logicalAnd()}.
	 *
	 * @param Context\LogicalAndContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitLogicalAnd(Context\LogicalAndContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::equality()}.
	 *
	 * @param Context\EqualityContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitEquality(Context\EqualityContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::relational()}.
	 *
	 * @param Context\RelationalContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitRelational(Context\RelationalContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::additive()}.
	 *
	 * @param Context\AdditiveContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitAdditive(Context\AdditiveContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::multiplicative()}.
	 *
	 * @param Context\MultiplicativeContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitMultiplicative(Context\MultiplicativeContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::unary()}.
	 *
	 * @param Context\UnaryContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitUnary(Context\UnaryContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::postfix()}.
	 *
	 * @param Context\PostfixContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitPostfix(Context\PostfixContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::incrementOp()}.
	 *
	 * @param Context\IncrementOpContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitIncrementOp(Context\IncrementOpContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::index()}.
	 *
	 * @param Context\IndexContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitIndex(Context\IndexContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::call()}.
	 *
	 * @param Context\CallContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitCall(Context\CallContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::primary()}.
	 *
	 * @param Context\PrimaryContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitPrimary(Context\PrimaryContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::arrayLiteral()}.
	 *
	 * @param Context\ArrayLiteralContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitArrayLiteral(Context\ArrayLiteralContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::arrayElements()}.
	 *
	 * @param Context\ArrayElementsContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitArrayElements(Context\ArrayElementsContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::arrayElement()}.
	 *
	 * @param Context\ArrayElementContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitArrayElement(Context\ArrayElementContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::builtinCall()}.
	 *
	 * @param Context\BuiltinCallContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitBuiltinCall(Context\BuiltinCallContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::exprList()}.
	 *
	 * @param Context\ExprListContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExprList(Context\ExprListContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::idList()}.
	 *
	 * @param Context\IdListContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitIdList(Context\IdListContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::literal()}.
	 *
	 * @param Context\LiteralContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitLiteral(Context\LiteralContext $context);
}