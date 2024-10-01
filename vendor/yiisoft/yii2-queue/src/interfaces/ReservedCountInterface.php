<?php
/**
 */

namespace yii\queue\interfaces;

/**
 * Reserved Count Interface
 *
 */
interface ReservedCountInterface
{
    /**
     * @return int
     */
    public function getReservedCount();
}
