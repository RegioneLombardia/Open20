<?php
/**
 */

namespace yii\queue\closure;

use function Opis\Closure\unserialize as opis_unserialize;
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
        $unserialized = opis_unserialize($this->serialized);
        if ($unserialized instanceof \Closure) {
            return $unserialized();
        }
        return $unserialized->execute($queue);
    }
}
