<?php
/**
 */

namespace yii\queue;

/**
 * Push Event.
 *
 */
class PushEvent extends JobEvent
{
    /**
     * @var int
     */
    public $delay;
    /**
     * @var mixed
     */
    public $priority;
}
