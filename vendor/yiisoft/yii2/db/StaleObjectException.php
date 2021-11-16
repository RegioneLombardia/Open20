<?php
/**
 */

namespace yii\db;

/**
 * @since 2.0
 */
class StaleObjectException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Stale Object Exception';
    }
}
