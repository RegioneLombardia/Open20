<?php

namespace luya\web\jsonld;

/**
 * JsonLd Country.
 *
 * @since 1.0.3
 */
class Country extends BaseThing implements CountryInterface
{
    use CountryTrait;
    
    /**
     * @inheritdoc
     */
    public function typeDefintion()
    {
        return 'Country';
    }
}
