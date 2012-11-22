<?php

namespace Rezzza\RulerBundle\Ruler\Serializer;

use Ruler\Operator\LogicalOperator;
use Rezzza\RulerBundle\Ruler\AsserterContainer;
use Rezzza\RulerBundle\Ruler\Proposition;

/**
 * GrammarSerializer
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class GrammarSerializer implements SerializerInterface
{
    /**
     * @param AsserterContainer $asserterContainer asserterContainer
     */
    public function __construct(AsserterContainer $asserterContainer)
    {
        $this->asserterContainer = $asserterContainer;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(PropositionInterface $proposition)
    {
        exit('todo');
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($data)
    {
        exit('todo');
    }
}
