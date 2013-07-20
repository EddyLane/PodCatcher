<?php
/**
 * Created by JetBrains PhpStorm.
 * User: edwardlane
 * Date: 20/07/2013
 * Time: 22:03
 * To change this template use File | Settings | File Templates.
 */

namespace Podcast\MainBundle\DQL;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
/**
 * DateDiffFunction ::= "DATE" "(" ArithmeticPrimary ")"
 */
class DateFunction extends FunctionNode
{
    public $dateExpression = null;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER); // (2)
        $parser->match(Lexer::T_OPEN_PARENTHESIS); // (3)
        $this->dateExpression = $parser->ArithmeticPrimary(); // (4)
        $parser->match(Lexer::T_CLOSE_PARENTHESIS); // (3)
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'DATE(' .
            $this->dateExpression->dispatch($sqlWalker) .
        ')'; // (7)
    }
}