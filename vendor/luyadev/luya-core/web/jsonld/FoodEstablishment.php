<?php

namespace luya\web\jsonld;

/**
 * Food Establishment
 *
 * @since 1.0.14
 */
class FoodEstablishment extends LocalBusiness implements FoodEstablishmentInterface
{
    use FoodEstablishmentTrait;

    /**
     * {@inheritDoc}
     */
    public function typeDefintion()
    {
        return 'FoodEstablishment';
    }
}
