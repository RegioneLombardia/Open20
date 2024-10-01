<?php

namespace luya\web\jsonld;

/**
 * JsonLd - Person
 *
 * A person (alive, dead, undead, or fictional).
 *
 *
 * @since 1.0.0
 */
class Person extends BaseThing implements PersonInterface
{
    /**
     * @inheritdoc
     */
    public function typeDefintion()
    {
        return 'Person';
    }
    
    use PersonTrait;
}
