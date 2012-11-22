<?php

namespace Rezzza\RulerBundle\Tests\Units\Ruler;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Rezzza\RulerBundle\Tests\Units\Test;
use Rezzza\RulerBundle\Ruler;
use Ruler\Rule;
use Ruler\Operator;

class PHPSerializer extends Test
{
    public function testDeserialize()
    {
        $boolean = new Ruler\Asserter\Boolean();
        $boolean->setIdent('boolean');

        $date = new Ruler\Asserter\Date();
        $date->setIdent('date');

        $asserterContainer = new Ruler\AsserterContainer();
        $asserterContainer->add($boolean);
        $asserterContainer->add($date);

        $serializer = new Ruler\Serializer\PHPSerializer($asserterContainer);

        /* ---- a proposition ---- */
        $propo = new Ruler\Proposition('toto', $asserterContainer->get('boolean'), '=', true);

        $this->if($data = $serializer->serialize($propo))
            ->object($serializer->deserialize($data))
            ->isEqualTo($propo);

        /* ---- on a rule --- */
        $rule = new Rule($propo);

        $this->if($data = $serializer->serialize($rule))
            ->object($serializer->deserialize($data))
            ->isEqualTo($rule);

        /* ---- on a rule + logical operators --- */
        $rule = new Rule(
            new Operator\LogicalAnd(array(
                $propo,
                $propo,
            ))
        );
        $data = $serializer->serialize($rule);

        $this->object($serializer->deserialize($data))
            ->isEqualTo($rule);

        /* ---- on a rule + logical operators + l..... --- */
        $rule = new Rule(
            new Operator\LogicalAnd(array(
                $propo,
                $propo,
                new Operator\LogicalOr(array(
                    $propo,
                    $propo,
                ))
            ))
        );
        $data = $serializer->serialize($rule);

        $this->object($serializer->deserialize($data))
            ->isEqualTo($rule);
    }
}
