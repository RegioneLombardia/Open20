<?php
/**
 */

namespace yii\queue\cli;

use yii\base\Event;

/**
 * Worker Event.
 *
 * @since 2.0.2
 */
class WorkerEvent extends Event
{
    /**
     * @var Queue
     * @inheritdoc
     */
    public $sender;
    /**
     * @var LoopInterface
     */
    public $loop;
    /**
     * @var null|int exit code
     */
    public $exitCode;
}
