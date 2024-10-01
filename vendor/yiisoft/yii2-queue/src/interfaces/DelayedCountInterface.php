<?php
/**
 */

namespace yii\queue\interfaces;

/**
 * Delayed Count Interface
 *
 */
interface DelayedCountInterface
{
    /**
     * @return int
     */
    public function getDelayedCount();
}
