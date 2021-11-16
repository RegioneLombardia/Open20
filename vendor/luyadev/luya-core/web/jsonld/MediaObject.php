<?php

namespace luya\web\jsonld;

/**
 * JsonLd MediaObject.
 *
 * @since 1.0.3
 */
class MediaObject extends BaseThing implements MediaObjectInterface
{
    use MediaObjectTrait;
    
    /**
     * @inheritdoc
     */
    public function typeDefintion()
    {
        return 'MediaObject';
    }
}
