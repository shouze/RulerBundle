<?php

namespace Rezzza\RulerBundle\Ruler\Serializer;

use Ruler\Operator\LogicalOperator;
use Rezzza\RulerBundle\Ruler\AsserterContainer;
use Rezzza\RulerBundle\Ruler\Proposition;

/**
 * PHPSerializer
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class PHPSerializer implements SerializerInterface
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
        return serialize($proposition);
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($data)
    {
        $data = unserialize($data);
        $this->resetAsserter($data);

        return $data;
    }

    /**
     * Reset asserter during a deserialization.
     *
     * @param PropositionInterface $proposition proposition
     */
    protected function resetAsserter(PropositionInterface $proposition)
    {
        if ($proposition instanceof Proposition) {
            $ident = $proposition
                ->getAsserter()
                ->getIdent();

            $proposition->setAsserter($this->asserterContainer->get($ident));

            return;
        } elseif ($proposition instanceof LogicalOperator) {
            $property = 'propositions';
        } else {
            $property = 'condition';
        }

        // @improve. poor, but at this moment no way to make it in an other way. Idea ?
        $reflection = new \ReflectionClass($proposition);
        $property   = $reflection->getProperty($property);
        $property->setAccessible(true);

        $conditions = $property->getValue($proposition);
        if (!is_array($conditions)) {
            $conditions = array($conditions);
        }

        foreach ($conditions as $condition) {
            $this->resetAsserter($condition);
        }
    }

}
