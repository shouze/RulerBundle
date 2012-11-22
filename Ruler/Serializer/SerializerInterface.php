<?php

namespace Rezzza\RulerBundle\Ruler\Serializer;

use Rezzza\RulerBundle\Ruler\Factory;
use Ruler\Rule;

/**
 * SerializerInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface SerializerInterface
{
    /**
     * @param PropositionInterface $proposition proposition
     *
     * @return string
     */
    public function serialize(PropositionInterface $proposition);

    /**
     * @param mixed   $data    data
     *
     * @return Rule|null
     */
    public function deserialize($data);
}
