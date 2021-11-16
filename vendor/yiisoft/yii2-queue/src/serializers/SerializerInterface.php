<?php
/**
 */

namespace yii\queue\serializers;

use yii\queue\JobInterface;

/**
 * Serializer Interface.
 *
 */
interface SerializerInterface
{
    /**
     * @param JobInterface|mixed $job
     * @return string
     */
    public function serialize($job);

    /**
     * @param string $serialized
     * @return JobInterface
     */
    public function unserialize($serialized);
}
