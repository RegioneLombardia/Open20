<?php
/**
 */

namespace yii\queue\interfaces;

/**
 * Done Count Interface
 *
 */
interface DoneCountInterface
{
    /**
     * @return int
     */
    public function getDoneCount();
}
