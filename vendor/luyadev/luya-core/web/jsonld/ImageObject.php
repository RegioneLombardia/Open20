<?php

namespace luya\web\jsonld;

/**
 * JsonLd ImageObject.
 *
 * @since 1.0.3
 */
class ImageObject extends BaseThing implements ImageObjectInterface
{
    use ImageObjectTrait;
    
    /**
     * @inheritdoc
     */
    public function typeDefintion()
    {
        return 'ImageObject';
    }
}
