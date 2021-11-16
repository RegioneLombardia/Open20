<?php

namespace luya\web\jsonld;

/**
 * JsonLd PropertyValue.
 *
 * @since 1.0.3
 */
class PropertyValue extends BaseThing implements PropertyValueInterface
{
    use PropertyValueTrait;
    
    /**
     * @inheritdoc
     */
    public function typeDefintion()
    {
        return 'PropertyValue';
    }
}
