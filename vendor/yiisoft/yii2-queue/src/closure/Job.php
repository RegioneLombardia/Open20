<?php
/**
 */

namespace yii\queue\closure;

use SuperClosure\Serializer;
use yii\queue\JobInterface;

/**
 * Closure Job.
 *
 */
class Job implements JobInterface
{
    /**
     * @var string serialized closure
     */
    public $serialized;


    /**
     * Unserializes and executes a closure.
     * @inheritdoc
     */
    public function execute($queue)
    {
        $serializer = new Serializer();
        $closure = $serializer->unserialize($this->serialized);
        return $closure();
    }
}
