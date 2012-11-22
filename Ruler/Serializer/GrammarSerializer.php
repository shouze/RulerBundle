<?php

namespace Rezzza\RulerBundle\Ruler\Serializer;

use Ruler\Operator\LogicalOperator;
use Ruler\Proposition as PropositionInterface;
use Rezzza\RulerBundle\Ruler\AsserterContainer;
use Rezzza\RulerBundle\Ruler\Proposition;
use Hoa\Compiler\Visitor\Dump;

from ('Hoa')
    -> import('Compiler.Visitor.Dump')
    -> import('File.Read')
    -> import('Compiler.Llk.~');

/**
 * GrammarSerializer
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class GrammarSerializer implements SerializerInterface
{
    private $asserterContainer;
    private $compiler;

    /**
     * @param AsserterContainer $asserterContainer asserterContainer
     */
    public function __construct(AsserterContainer $asserterContainer)
    {
        $this->asserterContainer = $asserterContainer;
        $this->compiler          = \Hoa\Compiler\Llk::load(
            new \Hoa\File\Read(__DIR__.'/Grammar/Grammar.pp')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(PropositionInterface $proposition)
    {
        exit('todo, transform proposition to a string.');
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($data)
    {
        $str = 'abcd != "12a" && efg.a >= 1';
        $ast = $this->compiler->parse($str);

        $dump     = new Dump();
        print "<pre>";
        var_dump($dump->visit($ast));
        print "</pre>";
        exit('todo');
    }
}
