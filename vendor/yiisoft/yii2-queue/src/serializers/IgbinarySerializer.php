<?php
/**
 */

namespace yii\queue\serializers;

use yii\base\BaseObject;

/**
 * Igbinary Serializer.
 *
 * It uses an alternative serializer available via PECL extension which produces
 * more compact data chunks significantly faster that native PHP one.
 *
 */
class IgbinarySerializer extends BaseObject implements SerializerInterface
{
    /**
     * @inheritdoc
     */
    public function serialize($job)
    {
        return igbinary_serialize($job);
    }

    /**
     * @inheritdoc
     */
    public function unserialize($serialized)
    {
        return igbinary_unserialize($serialized);
    }
}
