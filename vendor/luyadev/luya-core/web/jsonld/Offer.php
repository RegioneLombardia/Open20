<?php

namespace luya\web\jsonld;

/**
 * JsonLd Offer.
 *
 * @since 1.0.3
 */
class Offer extends BaseThing implements OfferInterface
{
    use OfferTrait;
    
    /**
     * @inheritdoc
     */
    public function typeDefintion()
    {
        return 'Offer';
    }
}
