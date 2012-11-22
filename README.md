RulerBundle
===========

[![Build Status](https://secure.travis-ci.org/rezzza/RulerBundle.png)](http://travis-ci.org/rezzza/RulerBundle)

A simple stateless production rules engine for Symfony 2.

Roadmap
=======

- Create dynamically Propositions via a Form and persist them on storage.
- Create a DSL to insert/fetch on storage. (using hoa/compiler ?)
- Create a standalone library + a bundle.

# Configuration

```
rezzza_ruler:
    serializer: rezzza.ruler.serializer.php # php serialization
    # "rezzza.ruler.serializer.grammar" will serialize rule at format
    # (cart.price_total = "30.00" && (cart.created_at >= "2011-06-10" OR ......)
    # You have to install hoa\compiler on your composer file.

    events:
        event.cart.paid: 'Cart paid'
	inferences:
		cart.price_total:
            # This will show this inferences only when event event.cart.paid
            # will be selected on UI.
            # This is optional.
            event:       [event.cart.paid]
			type:        decimal
			description: Cart total price is
		cart.created_at:
			type:        date
			description: Cart was created at
		cart.contain_product:
			type:        product
			# Your own asserter (see chapter Add an asserter)
			# You'll return a list of product as array.
            # Not yet implemented, we'll have to finish ui via forms.
			description: Cart contain product
```

# Usage

```php
<?php

//use Ruler\Rule;
//use Ruler\Operator;
//use Ruler\Context;

$inferenceContainer = $container->get('rezzza.ruler.inference_container');

$rule = new Rule(
    new Operator\LogicalAnd(array(
        $inferenceContainer->get('cart.price_total')->createProposition('>=', 100),
        $inferenceContainer->get('cart.created_at')->createProposition('>=', '2011-06-10'),
    ))
);

$context = new Context();
$context['cart.price_total'] = 110;
$context['cart.created_at'] = new \DateTime();

echo $rule->evaluate($context) ? 'OK': 'NOPE'; // OK;
```

# Serialization

To store rules on a storage, you can serialize it, store it on storage, fetch it from storage, and deserialize it. Context does not stay on storage.

```php
$serializer = $container->get('rezzza.ruler.serializer');

$rule = new Rule(
    new Operator\LogicalAnd(array(
        $inferenceContainer->get('cart.price_total')->createProposition('>=', 100),
        $inferenceContainer->get('cart.created_at')->createProposition('>=', '2011-06-10'),
    ))
);

$data = $serializer->serialize($rule); // will return a linear serialization of object.

$rule = $serializer->unserialize($data); // will be equals to $rule above :).
```

# Add an asserter.

In this example, we'll create `product` asserter, this one will fetch on storage a list of products.

## 1) Create asserter class

```php
<?php

namespace Acme\Bundle\Asserter\Product;

use Rezzza\RulerBundle\Ruler\Asserter\AbstractAsserter;
use Rezzza\RulerBundle\Ruler\Asserter\AsserterInterface;

class Product extends AbstractAsserter implements AsserterInterface
{
    public function __construct()
    {
        // here we have to define operators and them callback.
,
        // $left  is the product id you choosed on UI (WIP)
        // $right is what you entered on context with key "cart.contain_product"
        // We suppose here $right is an array and we would check if $left is in $right.

        $this->operators['contains'] = function ($left, $right) {
            return in_array($left, $right);
        };
    }
}
```

## 2) Define the service and tag it.

```xml
<services>
    <service id="acme.bundle.asserter.product" class="\Acme\Bundle\Asserter\Product">
        <tag name="rezzza.ruler.asserter" id="product" />
        <! --- here, product is the KEY used on config.yml, important ! -->
    </service>
</service>

```

That's all folks !

Glossary
==========

- Inference:   A group of proposition
- Proposition: A rule attached to an inference.

Any idea, suggestion ? [Create an issue](https://github.com/rezzza/RulerBundle/issues/new).
