<?php

namespace luya\web\jsonld;

/**
 * JsonLd - Place
 *
 * Entities that have a somewhat fixed, physical extension.
 *
 *
 * @since 1.0.0
 */
class Place extends BaseThing implements PlaceInterface
{
    /**
     * @inheritdoc
     */
    public function typeDefintion()
    {
        return 'Place';
    }
    
    use PlaceTrait;
}
