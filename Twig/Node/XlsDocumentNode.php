<?php

namespace MewesK\TwigExcelBundle\Twig\Node;

use Twig_Compiler;
use Twig_Node;
use Twig_Node_Expression;

/**
 * Class XlsDocumentNode
 *
 * @package MewesK\TwigExcelBundle\Twig\Node
 */
class XlsDocumentNode extends Twig_Node
{
    /**
     * @var bool
     */
    private $preCalculateFormulas;
    /**
     * @var null|string
     */
    private $diskCachingDirectory;

    /**
     * @param Twig_Node_Expression $properties
     * @param Twig_Node $body
     * @param int $line
     * @param string $tag
     * @param bool $preCalculateFormulas
     * @param null|string $diskCachingDirectory
     */
    public function __construct(Twig_Node_Expression $properties, Twig_Node $body, $line = 0, $tag = 'xlsdocument', $preCalculateFormulas = true, $diskCachingDirectory = null)
    {
        parent::__construct(['properties' => $properties, 'body' => $body], [], $line, $tag);
        $this->preCalculateFormulas = $preCalculateFormulas;
        $this->diskCachingDirectory = $diskCachingDirectory;
    }

    /**
     * @param Twig_Compiler $compiler
     */
    public function compile(Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write('$documentProperties = ')
            ->subcompile($this->getNode('properties'))
            ->raw(';' . PHP_EOL)
            ->write('$phpExcel = new MewesK\TwigExcelBundle\Wrapper\PhpExcelWrapper($context);' . PHP_EOL)
            ->write('$phpExcel->startDocument($documentProperties);' . PHP_EOL)
            ->write('unset($documentProperties);' . PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->addDebugInfo($this)
            ->write('$phpExcel->endDocument(' .
                ($this->preCalculateFormulas ? 'true' : 'false') . ', ' .
                ($this->diskCachingDirectory ? '\'' . $this->diskCachingDirectory . '\'' : 'null') . ');' . PHP_EOL)
            ->write('unset($phpExcel);' . PHP_EOL);

    }
}
