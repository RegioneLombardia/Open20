<?php
/**
 */

namespace yii\queue\interfaces;

/**
 * Waiting Count Interface
 *
 */
interface WaitingCountInterface
{
    /**
     * @return int
     */
    public function getWaitingCount();
}
