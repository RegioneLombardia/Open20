<?php

namespace luya\web\jsonld;

/**
 * Restaurant
 *
 * @since 1.0.14
 */
class Restaurant extends FoodEstablishment
{
    /**
     * {@inheritDoc}
     */
    public function typeDefintion()
    {
        return 'Restaurant';
    }
}
