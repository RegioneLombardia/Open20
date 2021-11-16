<?php
/**
 */

namespace yii\db;

/**
 * Exception represents an exception that is caused by violation of DB constraints.
 *
 * @since 2.0
 */
class IntegrityException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Integrity constraint violation';
    }
}
