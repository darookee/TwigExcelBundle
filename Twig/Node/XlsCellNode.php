<?php

namespace MewesK\TwigExcelBundle\Twig\Node;

use Twig_Compiler;
use Twig_Node;
use Twig_Node_Expression;

/**
 * Class XlsCellNode
 *
 * @package MewesK\TwigExcelBundle\Twig\Node
 */
class XlsCellNode extends Twig_Node
{
    /**
     * @param Twig_Node_Expression $index
     * @param Twig_Node_Expression $properties
     * @param Twig_Node $body
     * @param int $line
     * @param string $tag
     */
    public function __construct(Twig_Node_Expression $index, Twig_Node_Expression $properties, Twig_Node $body, $line = 0, $tag = 'xlscell')
    {
        parent::__construct(['index' => $index, 'properties' => $properties, 'body' => $body], [], $line, $tag);
    }

    /**
     * @param Twig_Compiler $compiler
     */
    public function compile(Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write('$cellIndex = ')
            ->subcompile($this->getNode('index'))
            ->raw(';' . PHP_EOL)
            ->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write('$cellValue = trim(ob_get_clean());' . PHP_EOL)
            ->write('$cellProperties = ')
            ->subcompile($this->getNode('properties'))
            ->raw(';' . PHP_EOL)
            ->write('$phpExcel->startCell($cellIndex, $cellValue, $cellProperties);' . PHP_EOL)
            ->write('unset($cellIndex, $cellValue, $cellProperties);' . PHP_EOL)
            ->write('$phpExcel->endCell();' . PHP_EOL);
    }
}
