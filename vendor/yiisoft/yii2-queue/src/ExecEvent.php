<?php
/**
 */

namespace yii\queue;

/**
 * Exec Event.
 *
 */
class ExecEvent extends JobEvent
{
    /**
     * @var int attempt number.
     */
    public $attempt;
    /**
     * @var mixed result of a job execution in case job is done.
     * @since 2.1.1
     */
    public $result;
    /**
     * @var null|\Exception|\Throwable
     * @since 2.1.1
     */
    public $error;
    /**
     * @var null|bool
     * @since 2.1.1
     */
    public $retry;
}
