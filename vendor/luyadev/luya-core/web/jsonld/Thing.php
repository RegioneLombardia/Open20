<?php

namespace luya\web\jsonld;

/**
 * JsonLd - Thing
 *
 * The most generic type of item.
 *
 *
 * @since 1.0.0
 */
class Thing extends BaseThing
{
    /**
     * @inheritdoc
     */
    public function typeDefintion()
    {
        return 'Thing';
    }
}
