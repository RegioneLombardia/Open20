<?php
/**
 */

namespace yii\queue\cli;

/**
 * Loop Interface.
 *
 * @since 2.0.2
 */
interface LoopInterface
{
    /**
     * @return bool whether to continue listening of the queue.
     */
    public function canContinue();
}
