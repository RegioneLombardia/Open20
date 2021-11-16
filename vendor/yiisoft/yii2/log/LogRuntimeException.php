<?php
/**
 */

namespace yii\log;

/**
 * LogRuntimeException represents an exception caused by problems with log delivery.
 *
 * @since 2.0.14
 */
class LogRuntimeException extends \yii\base\Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Log Runtime';
    }
}
